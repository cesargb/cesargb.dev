import {
    AdditiveBlending,
    BufferGeometry,
    CanvasTexture,
    Color,
    Float32BufferAttribute,
    LineBasicMaterial,
    LineSegments,
    PerspectiveCamera,
    Points,
    PointsMaterial,
    Raycaster,
    Scene,
    SRGBColorSpace,
    Sprite,
    SpriteMaterial,
    Vector2,
    Vector3,
    WebGLRenderer,
} from 'three';

/*
 * A trip through space where every star is a technology from App\Data\Skills.
 * Stars live in a tunnel along -Z and travel towards the camera; when one passes the
 * camera it goes back to the far end, so the trip never ends.
 */

const DEPTH = 360; // length of the tunnel
const NEAR = 6; // how far past the camera a star travels before wrapping
const FOV = 60;
const CRUISE = 22; // units per second once the trip has warmed up
const START = 1.5; // units per second at the very beginning
const WARM_UP = 18; // seconds to reach cruise speed
const MAX_SPEED = 150;
const BACKGROUND_STARS = 2400;

const section = document.querySelector('[data-xp]');
const dataElement = document.getElementById('xp-data');

if (section && dataElement) {
    start(section, JSON.parse(dataElement.textContent));
}

function start(section, categories) {
    const canvas = section.querySelector('canvas');
    const labelLayer = section.querySelector('.xp-labels');
    const speedBar = section.querySelector('[data-xp-speed]');
    const speedLabel = section.querySelector('[data-xp-speed-label]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let renderer;

    try {
        renderer = new WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
    } catch {
        section.classList.add('is-unsupported');

        return;
    }

    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.outputColorSpace = SRGBColorSpace;

    const scene = new Scene();
    const camera = new PerspectiveCamera(FOV, 1, 0.1, DEPTH + 40);
    const tanHalfFov = Math.tan((FOV * Math.PI) / 360);
    const glow = glowTexture();

    const skills = createSkillStars(scene, categories, glow, labelLayer);
    const background = createBackgroundStars(scene, glow);

    // --- Size -----------------------------------------------------------------------
    let width = 1;
    let height = 1;

    const resize = () => {
        width = section.clientWidth;
        height = section.clientHeight;
        renderer.setSize(width, height, false);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
    };

    new ResizeObserver(resize).observe(section);
    resize();

    // --- Speed ----------------------------------------------------------------------
    let elapsed = 0;
    let speed = reducedMotion ? 0 : START;
    let boost = 0;
    let paused = false;
    let holding = false;

    const baseSpeed = () => {
        if (reducedMotion) {
            return 0;
        }

        const t = Math.min(elapsed / WARM_UP, 1);

        return START + (CRUISE - START) * t * t * (3 - 2 * t);
    };

    const targetSpeed = () => {
        if (paused || holding) {
            return 0;
        }

        const target = Math.max(baseSpeed() + boost, 0);

        // Slow down while a star is selected, so its label can be read.
        return Math.min(active ? target * 0.3 : target, MAX_SPEED);
    };

    const addBoost = (amount) => {
        boost = Math.max(Math.min(boost + amount, MAX_SPEED - baseSpeed()), -baseSpeed());
    };

    // --- Pointer --------------------------------------------------------------------
    const pointer = new Vector2(0, 0); // normalized device coordinates
    const raycaster = new Raycaster();
    let active = null;
    let drag = null;
    let holdTimer = null;

    const updatePointer = (event) => {
        const rect = canvas.getBoundingClientRect();
        pointer.set(
            ((event.clientX - rect.left) / rect.width) * 2 - 1,
            -((event.clientY - rect.top) / rect.height) * 2 + 1,
        );
    };

    const pick = () => {
        raycaster.setFromCamera(pointer, camera);
        const hit = raycaster.intersectObjects(skills.map((skill) => skill.sprite), false)[0];

        return hit ? skills.find((skill) => skill.sprite === hit.object) : null;
    };

    const setActive = (skill) => {
        if (active === skill) {
            return;
        }

        active?.label.classList.remove('is-active');
        active = skill;
        active?.label.classList.add('is-active');
    };

    canvas.addEventListener('pointerdown', (event) => {
        canvas.setPointerCapture(event.pointerId);
        updatePointer(event);
        drag = { x: event.clientX, y: event.clientY, moved: 0 };
        holdTimer = setTimeout(() => {
            holding = true;
        }, 320);
    });

    canvas.addEventListener('pointermove', (event) => {
        updatePointer(event);

        if (!drag) {
            if (event.pointerType === 'mouse') {
                setActive(pick());
            }

            return;
        }

        const distance = Math.hypot(event.clientX - drag.x, event.clientY - drag.y);
        drag.x = event.clientX;
        drag.y = event.clientY;
        drag.moved += distance;

        if (drag.moved > 8) {
            clearTimeout(holdTimer);
            holding = false;
            addBoost(distance * 0.35);
        }
    });

    const release = (event) => {
        clearTimeout(holdTimer);

        if (drag && drag.moved <= 8 && !holding && event.type === 'pointerup') {
            // A tap: select the star under the finger, or clear the selection.
            setActive(pick());
        }

        holding = false;
        drag = null;
    };

    canvas.addEventListener('pointerup', release);
    canvas.addEventListener('pointercancel', release);
    canvas.addEventListener('pointerleave', (event) => {
        if (event.pointerType === 'mouse' && !drag) {
            setActive(null);
            pointer.set(0, 0);
        }
    });

    canvas.addEventListener('keydown', (event) => {
        if (event.code === 'Space') {
            event.preventDefault();
            paused = !paused;
        } else if (event.code === 'ArrowUp') {
            event.preventDefault();
            paused = false;
            addBoost(15);
        } else if (event.code === 'ArrowDown') {
            event.preventDefault();
            addBoost(-15);
        }
    });

    // --- Loop -----------------------------------------------------------------------
    const projected = new Vector3();
    let visible = true;
    let last = performance.now();
    let travelling = false;

    const frame = (now) => {
        const dt = Math.min((now - last) / 1000, 0.1);
        last = now;

        if (!paused) {
            elapsed += dt;
        }

        boost *= Math.exp(-dt * 0.6);
        speed += (targetSpeed() - speed) * (1 - Math.exp(-dt * 1.8));

        // Camera: a soft parallax towards the pointer and a slow roll.
        camera.rotation.y += (-pointer.x * 0.14 - camera.rotation.y) * (1 - Math.exp(-dt * 2));
        camera.rotation.x += (pointer.y * 0.09 - camera.rotation.x) * (1 - Math.exp(-dt * 2));
        camera.rotation.z += speed * dt * 0.0012;
        camera.updateMatrixWorld();

        const step = speed * dt;

        for (const skill of skills) {
            moveSkill(skill, step, now / 1000);
            placeLabel(skill);
        }

        background.update(step, speed);
        renderer.render(scene, camera);
        updateHud();

        if (visible) {
            requestAnimationFrame(frame);
        }
    };

    function moveSkill(skill, step, time) {
        const { sprite } = skill;
        sprite.position.z += step;

        if (sprite.position.z > NEAR) {
            sprite.position.z -= DEPTH + NEAR;
            placeOnRing(sprite.position, skill.radius);
        }

        const distance = -sprite.position.z;
        const fadeIn = clamp((DEPTH - distance) / (DEPTH * 0.3), 0, 1);
        const pulse = 1 + Math.sin(time * 1.6 + skill.phase) * 0.05;
        const highlight = skill === active ? 1.35 : 1;

        sprite.material.opacity = fadeIn;
        skill.core.material.opacity = fadeIn;
        sprite.scale.setScalar(skill.size * pulse * highlight);
        skill.fade = fadeIn;
    }

    function placeLabel(skill) {
        const { sprite, label } = skill;
        const distance = -sprite.position.z;
        const near = clamp((190 - distance) / 70, 0, 1);
        let opacity = near * skill.fade;

        projected.copy(sprite.position).project(camera);

        if (projected.z > 1 || distance < 1 || Math.abs(projected.x) > 1.2 || Math.abs(projected.y) > 1.2) {
            opacity = 0;
        }

        if (skill === active && opacity > 0) {
            opacity = 1;
        }

        if (opacity <= 0.01) {
            if (skill.shown) {
                label.style.opacity = '0';
                skill.shown = false;
            }

            return;
        }

        const x = (projected.x * 0.5 + 0.5) * width;
        const y = (-projected.y * 0.5 + 0.5) * height;
        const radius = (sprite.scale.x * 0.5 * (height / 2)) / (Math.max(distance, 0.1) * tanHalfFov);
        const scale = clamp(36 / distance, 0.7, 1.5);

        label.style.opacity = opacity.toFixed(2);
        label.style.transform = `translate3d(${x.toFixed(1)}px, ${(y + radius * 0.3 + 4).toFixed(1)}px, 0) translateX(-50%) scale(${scale.toFixed(2)})`;
        skill.shown = true;
    }

    let hudSpeed = -1;
    let hudPaused = null;

    function updateHud() {
        const isPaused = paused || holding;
        const rounded = Math.round((speed / MAX_SPEED) * 100);

        if (rounded !== hudSpeed) {
            speedBar.style.width = `${rounded}%`;
            hudSpeed = rounded;
        }

        if (isPaused !== hudPaused) {
            speedLabel.textContent = isPaused ? speedLabel.dataset.paused : speedLabel.dataset.speed;
            hudPaused = isPaused;
        }

        const isTravelling = speed > CRUISE * 0.5;

        if (isTravelling !== travelling) {
            section.classList.toggle('is-travelling', isTravelling);
            travelling = isTravelling;
        }
    }

    // Only render while the space is on screen and the tab is visible.
    const resume = () => {
        if (!visible) {
            visible = true;
            last = performance.now();
            requestAnimationFrame(frame);
        }
    };

    new IntersectionObserver(([entry]) => {
        if (entry.isIntersecting && !document.hidden) {
            resume();
        } else {
            visible = false;
        }
    }).observe(section);

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            visible = false;
        } else {
            resume();
        }
    });

    section.classList.add('is-ready');
    requestAnimationFrame(frame);
}

/**
 * One sprite per skill: bigger and closer to the centre of the tunnel the more weight
 * it has, spread along the tunnel so they arrive one after another.
 */
function createSkillStars(scene, categories, glow, labelLayer) {
    const all = categories.flatMap((category) =>
        category.skills.map((skill) => ({ ...skill, category })),
    );

    shuffle(all);

    return all.map((skill, index) => {
        const color = new Color(skill.category.color);
        const sprite = new Sprite(
            new SpriteMaterial({
                map: glow,
                color,
                blending: AdditiveBlending,
                depthWrite: false,
                transparent: true,
            }),
        );

        const core = new Sprite(
            new SpriteMaterial({ map: glow, color: 0xffffff, blending: AdditiveBlending, depthWrite: false, transparent: true }),
        );
        core.scale.setScalar(0.22);
        sprite.add(core);

        const radius = 4 + (5 - skill.weight) * 3 + Math.random() * 7;
        sprite.position.z = -DEPTH * ((index + Math.random() * 0.6) / all.length) + NEAR - 12;
        placeOnRing(sprite.position, radius);
        scene.add(sprite);

        const label = document.createElement('span');
        label.className = 'xp-star-label';
        label.style.setProperty('--star-color', skill.category.color);
        label.style.fontSize = `${11 + skill.weight * 1.5}px`;
        label.append(skill.name);

        const detail = document.createElement('small');
        detail.textContent = `${skill.category.name} · ${'●'.repeat(skill.weight)}${'○'.repeat(5 - skill.weight)}`;
        label.append(detail);
        labelLayer.append(label);

        return {
            sprite,
            core,
            label,
            radius,
            size: 0.7 * Math.pow(skill.weight, 1.4),
            phase: Math.random() * Math.PI * 2,
            fade: 0,
            shown: false,
        };
    });
}

/**
 * Small white stars that stretch into streaks as the speed grows.
 */
function createBackgroundStars(scene, glow) {
    const heads = new Float32Array(BACKGROUND_STARS * 3);
    const trails = new Float32Array(BACKGROUND_STARS * 6);
    const position = new Vector3();

    for (let i = 0; i < BACKGROUND_STARS; i++) {
        position.z = -Math.random() * DEPTH + NEAR;
        placeOnRing(position, 3 + Math.pow(Math.random(), 0.6) * 90);
        heads.set([position.x, position.y, position.z], i * 3);
    }

    const pointsGeometry = new BufferGeometry();
    pointsGeometry.setAttribute('position', new Float32BufferAttribute(heads, 3));

    const points = new Points(
        pointsGeometry,
        new PointsMaterial({
            map: glow,
            color: 0xdfe3ff,
            size: 0.6,
            transparent: true,
            depthWrite: false,
            blending: AdditiveBlending,
        }),
    );

    const linesGeometry = new BufferGeometry();
    linesGeometry.setAttribute('position', new Float32BufferAttribute(trails, 3));

    const lines = new LineSegments(
        linesGeometry,
        new LineBasicMaterial({ color: 0xc8ceff, transparent: true, opacity: 0, depthWrite: false, blending: AdditiveBlending }),
    );

    scene.add(points, lines);

    const headAttribute = pointsGeometry.getAttribute('position');
    const trailAttribute = linesGeometry.getAttribute('position');

    return {
        update(step, speed) {
            const trail = Math.min(speed * 0.06, 9);

            for (let i = 0; i < BACKGROUND_STARS; i++) {
                const h = i * 3;
                let z = heads[h + 2] + step;

                if (z > NEAR) {
                    z -= DEPTH + NEAR;
                    position.z = z;
                    placeOnRing(position, 3 + Math.pow(Math.random(), 0.6) * 90);
                    heads[h] = position.x;
                    heads[h + 1] = position.y;
                }

                heads[h + 2] = z;

                const t = i * 6;
                trails[t] = heads[h];
                trails[t + 1] = heads[h + 1];
                trails[t + 2] = z;
                trails[t + 3] = heads[h];
                trails[t + 4] = heads[h + 1];
                trails[t + 5] = z - trail;
            }

            lines.material.opacity = clamp((speed - 8) / 40, 0, 0.7);
            headAttribute.needsUpdate = true;
            trailAttribute.needsUpdate = true;
        },
    };
}

function glowTexture() {
    const size = 128;
    const canvas = document.createElement('canvas');
    canvas.width = canvas.height = size;

    const context = canvas.getContext('2d');
    const gradient = context.createRadialGradient(size / 2, size / 2, 0, size / 2, size / 2, size / 2);
    gradient.addColorStop(0, 'rgba(255,255,255,1)');
    gradient.addColorStop(0.12, 'rgba(255,255,255,0.9)');
    gradient.addColorStop(0.3, 'rgba(255,255,255,0.3)');
    gradient.addColorStop(0.6, 'rgba(255,255,255,0.06)');
    gradient.addColorStop(1, 'rgba(255,255,255,0)');
    context.fillStyle = gradient;
    context.fillRect(0, 0, size, size);

    const texture = new CanvasTexture(canvas);
    texture.colorSpace = SRGBColorSpace;

    return texture;
}

function placeOnRing(position, radius) {
    const angle = Math.random() * Math.PI * 2;
    position.x = Math.cos(angle) * radius;
    position.y = Math.sin(angle) * radius;
}

function shuffle(items) {
    for (let i = items.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [items[i], items[j]] = [items[j], items[i]];
    }
}

function clamp(value, min, max) {
    return Math.min(Math.max(value, min), max);
}
