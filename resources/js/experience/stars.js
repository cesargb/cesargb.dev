import {
    AdditiveBlending,
    BufferGeometry,
    CanvasTexture,
    Color,
    Float32BufferAttribute,
    LineBasicMaterial,
    LineSegments,
    Points,
    PointsMaterial,
    SRGBColorSpace,
    Sprite,
    SpriteMaterial,
    Vector3,
} from 'three';
import { TUNNEL_RADIUS } from './tunnel.js';

const DUST = 2000;
const OUTER_STARS = 1500;

/**
 * One sprite per skill, fixed somewhere along the tunnel. The more weight, the bigger and
 * the closer to the axis (but never on it, so the camera never flies through a star).
 */
export function createSkillStars(scene, tunnel, categories, glow, labelLayer) {
    const all = categories.flatMap((category) =>
        category.skills.map((skill) => ({ ...skill, category })),
    );

    shuffle(all);

    return all.map((skill, index) => {
        const sprite = new Sprite(
            new SpriteMaterial({
                map: glow,
                color: new Color(skill.category.color),
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

        // Leave the first stretch of the trip empty so the first star arrives after a moment.
        const u = 0.02 + ((index + Math.random() * 0.6) / all.length) * 0.98;
        const radius = TUNNEL_RADIUS * (0.3 + (5 - skill.weight) * 0.1 + Math.random() * 0.1);
        tunnel.around(u, radius, Math.random() * Math.PI * 2, sprite.position);
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
            size: 0.7 * Math.pow(skill.weight, 1.4),
            phase: Math.random() * Math.PI * 2,
            fade: 0,
            shown: false,
        };
    });
}

/**
 * Dust inside the tube that stretches into streaks along the tunnel as the speed grows.
 */
export function createDust(scene, tunnel, glow) {
    const heads = new Float32Array(DUST * 3);
    const tangents = new Float32Array(DUST * 3);
    const trails = new Float32Array(DUST * 6);
    const point = new Vector3();

    for (let i = 0; i < DUST; i++) {
        const u = Math.random();
        tunnel.around(u, Math.sqrt(Math.random()) * TUNNEL_RADIUS * 0.95, Math.random() * Math.PI * 2, point);
        heads.set([point.x, point.y, point.z], i * 3);
        const tangent = tunnel.curve.getTangentAt(u);
        tangents.set([tangent.x, tangent.y, tangent.z], i * 3);
        trails.set([point.x, point.y, point.z, point.x, point.y, point.z], i * 6);
    }

    const pointsGeometry = new BufferGeometry();
    pointsGeometry.setAttribute('position', new Float32BufferAttribute(heads, 3));
    scene.add(
        new Points(
            pointsGeometry,
            new PointsMaterial({
                map: glow,
                color: 0xdfe3ff,
                size: 0.5,
                transparent: true,
                depthWrite: false,
                blending: AdditiveBlending,
            }),
        ),
    );

    const linesGeometry = new BufferGeometry();
    linesGeometry.setAttribute('position', new Float32BufferAttribute(trails, 3));
    const lines = new LineSegments(
        linesGeometry,
        new LineBasicMaterial({ color: 0xc8ceff, transparent: true, opacity: 0, depthWrite: false, blending: AdditiveBlending }),
    );
    scene.add(lines);

    const trailAttribute = linesGeometry.getAttribute('position');
    let lastTrail = 0;

    return {
        update(speed) {
            const trail = Math.min(speed * 0.06, 9);
            lines.material.opacity = Math.min(Math.max((speed - 8) / 40, 0), 0.7);

            if (Math.abs(trail - lastTrail) < 0.02) {
                return;
            }

            lastTrail = trail;

            for (let i = 0; i < DUST; i++) {
                const h = i * 3;
                const t = i * 6 + 3;
                trails[t] = heads[h] + tangents[h] * trail;
                trails[t + 1] = heads[h + 1] + tangents[h + 1] * trail;
                trails[t + 2] = heads[h + 2] + tangents[h + 2] * trail;
            }

            trailAttribute.needsUpdate = true;
        },
    };
}

/**
 * Static stars outside the tube, seen through the wall: the space around the tunnel.
 */
export function createOuterStars(scene, tunnel, glow) {
    const positions = new Float32Array(OUTER_STARS * 3);
    const point = new Vector3();

    for (let i = 0; i < OUTER_STARS; i++) {
        const radius = TUNNEL_RADIUS * (1.5 + Math.random() * 4.5);
        tunnel.around(Math.random(), radius, Math.random() * Math.PI * 2, point);
        positions.set([point.x, point.y, point.z], i * 3);
    }

    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new Float32BufferAttribute(positions, 3));
    scene.add(
        new Points(
            geometry,
            new PointsMaterial({
                map: glow,
                color: 0xffffff,
                size: 1.1,
                transparent: true,
                opacity: 0.8,
                depthWrite: false,
                blending: AdditiveBlending,
            }),
        ),
    );
}

export function glowTexture() {
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

function shuffle(items) {
    for (let i = items.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [items[i], items[j]] = [items[j], items[i]];
    }
}
