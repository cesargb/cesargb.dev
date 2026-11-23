import { Fog, PerspectiveCamera, Raycaster, Scene, SRGBColorSpace, Vector2, Vector3, WebGLRenderer } from 'three';
import { createDust, createOuterStars, createSkillStars, glowTexture } from './experience/stars.js';
import { createTunnel } from './experience/tunnel.js';

/*
 * A trip through a winding tunnel where every star is a technology from App\Data\Skills.
 * The tunnel is a closed loop, so the trip never ends. The page shows a CSS preview of
 * the tunnel (`.xp-preview`) until the first frame of this scene is rendered.
 */

const FOV = 62;
const CRUISE = 22; // units per second once the trip has warmed up
const START = 1.5; // units per second at the very beginning
const WARM_UP = 18; // seconds to reach cruise speed
const MAX_SPEED = 150;
const LOOK_AHEAD = 14; // how far ahead along the tunnel the camera looks
const LABEL_RANGE = 150; // labels start to appear at this distance

const section = document.querySelector('[data-xp]');
const dataElement = document.getElementById('xp-data');

if (section && dataElement && !section.classList.contains('is-unsupported')) {
    start(section, JSON.parse(dataElement.textContent));
}

function start(section, categories) {
    const canvas = section.querySelector('canvas');
    const labelLayer = section.querySelector('.xp-labels');
    const speedBar = section.querySelector('[data-xp-speed]');
    const speedLabel = section.querySelector('[data-xp-speed-label]');
    const toggle = section.querySelector('[data-xp-toggle]');
    const toggleLabel = section.querySelector('[data-xp-toggle-label]');
    const status = section.querySelector('[data-xp-status]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let renderer;

    try {
        renderer = new WebGLRenderer({ canvas, antialias: true, alpha: true, powerPreference: 'high-performance' });
    } catch {
        section.classList.remove('is-loading');
        section.classList.add('is-unsupported');

        return;
    }

    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.outputColorSpace = SRGBColorSpace;

    const scene = new Scene();
    scene.fog = new Fog(0x0a0a0b, 30, 240);

    const camera = new PerspectiveCamera(FOV, 1, 0.1, 400);
    const tanHalfFov = Math.tan((FOV * Math.PI) / 360);
    const glow = glowTexture();

    const tunnel = createTunnel(scene);
    const skills = createSkillStars(scene, tunnel, categories, glow, labelLayer);
    const dust = createDust(scene, tunnel, glow);
    createOuterStars(scene, tunnel, glow);

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

    toggle.addEventListener('click', () => {
        paused = !paused;
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

    // --- Camera ---------------------------------------------------------------------
    const lookTarget = new Vector3();
    const tangent = new Vector3();
    const tangentAhead = new Vector3();
    const right = new Vector3();
    let travelled = 0;
    let roll = 0;
    let parallaxX = 0;
    let parallaxY = 0;

    function moveCamera(step, dt) {
        const safeStep = Number.isFinite(step) ? step : 0;
        travelled = (((travelled + safeStep) % tunnel.length) + tunnel.length) % tunnel.length;
        const u = travelled / tunnel.length;
        const ahead = (u + LOOK_AHEAD / tunnel.length) % 1;

        tunnel.curve.getPointAt(u, camera.position);
        tunnel.curve.getPointAt(ahead, lookTarget);
        camera.up.set(0, 1, 0);
        camera.lookAt(lookTarget);

        // Bank into the curves: how much the tunnel turns towards the camera's right.
        tunnel.curve.getTangentAt(u, tangent);
        tunnel.curve.getTangentAt((u + (LOOK_AHEAD * 3) / tunnel.length) % 1, tangentAhead);
        right.set(1, 0, 0).applyQuaternion(camera.quaternion);
        const turn = tangentAhead.sub(tangent).dot(right);
        roll += (clamp(-turn * 1.2, -0.4, 0.4) - roll) * (1 - Math.exp(-dt * 1.5));

        // A soft parallax towards the pointer.
        parallaxX += (-pointer.x * 0.14 - parallaxX) * (1 - Math.exp(-dt * 2));
        parallaxY += (pointer.y * 0.09 - parallaxY) * (1 - Math.exp(-dt * 2));

        camera.rotateZ(roll);
        camera.rotateY(parallaxX);
        camera.rotateX(parallaxY);
        camera.updateMatrixWorld();
    }

    // --- Loop -----------------------------------------------------------------------
    const projected = new Vector3();
    let visible = true;
    let last = performance.now();
    let travelling = false;
    let ready = false;

    const frame = (now) => {
        // rAF timestamps mark the start of the frame and can be older than `last`.
        const dt = clamp((now - last) / 1000, 0, 0.1);
        last = now;

        if (!paused) {
            elapsed += dt;
        }

        boost *= Math.exp(-dt * 0.6);
        speed += (targetSpeed() - speed) * (1 - Math.exp(-dt * 1.8));

        moveCamera(speed * dt, dt);

        for (const skill of skills) {
            updateSkill(skill, now / 1000);
        }

        dust.update(speed);
        renderer.render(scene, camera);
        updateHud();

        if (!ready) {
            ready = true;
            section.classList.add('is-ready');
        }

        if (visible) {
            requestAnimationFrame(frame);
        }
    };

    function updateSkill(skill, time) {
        const { sprite, label } = skill;
        const distance = camera.position.distanceTo(sprite.position);
        const near = clamp((distance - 3) / 6, 0, 1);
        const pulse = 1 + Math.sin(time * 1.6 + skill.phase) * 0.05;
        const highlight = skill === active ? 1.35 : 1;

        sprite.material.opacity = near;
        skill.core.material.opacity = near;
        sprite.scale.setScalar(skill.size * pulse * highlight);

        let opacity = clamp((LABEL_RANGE - distance) / 50, 0, 1) * near;

        projected.copy(sprite.position).project(camera);

        if (projected.z > 1 || Math.abs(projected.x) > 1.2 || Math.abs(projected.y) > 1.2) {
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
    let togglePaused = false;

    function updateHud() {
        const isPaused = paused || holding;
        const rounded = Math.round((speed / MAX_SPEED) * 100);

        // The button and the screen reader status follow the explicit pause only, not a hold.
        if (paused !== togglePaused) {
            toggle.setAttribute('aria-pressed', String(paused));
            toggleLabel.textContent = paused ? toggle.dataset.resume : toggle.dataset.pause;
            status.textContent = paused ? speedLabel.dataset.paused : speedLabel.dataset.speed;
            togglePaused = paused;
        }

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

    requestAnimationFrame(frame);
}

function clamp(value, min, max) {
    return Math.min(Math.max(value, min), max);
}
