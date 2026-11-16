import {
    AdditiveBlending,
    BufferGeometry,
    CatmullRomCurve3,
    Float32BufferAttribute,
    LineBasicMaterial,
    LineSegments,
    Vector3,
} from 'three';

export const TUNNEL_RADIUS = 14;

const SEGMENTS = 900; // samples along the curve
const SIDES = 16; // longitudinal lines of the wall
const RING_EVERY = 6; // a ring every N samples

/**
 * A closed, winding tube the camera travels through forever. Returns the curve plus a
 * helper to place things around its axis using the curve's Frenet frames.
 */
export function createTunnel(scene) {
    const controlPoints = [];

    for (let i = 0; i < 16; i++) {
        const a = (i / 16) * Math.PI * 2;
        const r = 220 + 60 * Math.sin(3 * a);
        controlPoints.push(new Vector3(Math.cos(a) * r, 40 * Math.sin(2 * a) + 20 * Math.cos(5 * a), Math.sin(a) * r));
    }

    const curve = new CatmullRomCurve3(controlPoints, true, 'centripetal');
    const length = curve.getLength();
    const frames = curve.computeFrenetFrames(SEGMENTS, true);
    const centers = Array.from({ length: SEGMENTS }, (_, i) => curve.getPointAt(i / SEGMENTS));

    /**
     * Writes into `target` the point at `radius` from the axis, `angle` around it, at the
     * position `u` (0..1) of the curve.
     */
    const around = (u, radius, angle, target = new Vector3()) => {
        const wrapped = ((u % 1) + 1) % 1;
        const index = Math.round(wrapped * SEGMENTS) % SEGMENTS;
        const center = curve.getPointAt(wrapped);
        const cos = Math.cos(angle) * radius;
        const sin = Math.sin(angle) * radius;

        return target
            .copy(center)
            .addScaledVector(frames.normals[index], cos)
            .addScaledVector(frames.binormals[index], sin);
    };

    const wallPoint = (i, side) => {
        const index = i % SEGMENTS;
        const angle = (side / SIDES) * Math.PI * 2;

        return new Vector3()
            .copy(centers[index])
            .addScaledVector(frames.normals[index], Math.cos(angle) * TUNNEL_RADIUS)
            .addScaledVector(frames.binormals[index], Math.sin(angle) * TUNNEL_RADIUS);
    };

    const longitudinal = [];
    const rings = [];

    for (let i = 0; i < SEGMENTS; i++) {
        for (let side = 0; side < SIDES; side++) {
            const a = wallPoint(i, side);
            longitudinal.push(a.x, a.y, a.z);
            const b = wallPoint(i + 1, side);
            longitudinal.push(b.x, b.y, b.z);

            if (i % RING_EVERY === 0) {
                const c = wallPoint(i, (side + 1) % SIDES);
                rings.push(a.x, a.y, a.z, c.x, c.y, c.z);
            }
        }
    }

    scene.add(
        lines(longitudinal, 0x7c83ff, 0.16),
        lines(rings, 0x9aa0ff, 0.32),
    );

    return { curve, length, around };
}

function lines(positions, color, opacity) {
    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new Float32BufferAttribute(positions, 3));

    return new LineSegments(
        geometry,
        new LineBasicMaterial({ color, transparent: true, opacity, depthWrite: false, blending: AdditiveBlending }),
    );
}
