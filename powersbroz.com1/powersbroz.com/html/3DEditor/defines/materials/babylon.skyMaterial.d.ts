
declare module BABYLON {
    class SkyMaterial extends Material {
        luminance: number;
        turbidity: number;
        reileigh: number;
        mieCoefficient: number;
        mieDirectionalG: number;
        distance: number;
        inclination: number;
        azimuth: number;
        private _sunPosition;
        private _renderId;
        private _defines;
        private _cachedDefines;
        constructor(name: string, scene: Scene);
        needAlphaBlending(): boolean;
        needAlphaTesting(): boolean;
        getAlphaTestTexture(): BaseTexture;
        private _checkCache(scene, mesh?, useInstances?);
        isReady(mesh?: AbstractMesh, useInstances?: boolean): boolean;
        bindOnlyWorldMatrix(world: Matrix): void;
        bind(world: Matrix, mesh?: Mesh): void;
        getAnimatables(): IAnimatable[];
        dispose(forceDisposeEffect?: boolean): void;
        clone(name: string): SkyMaterial;
        serialize(): any;
        static Parse(source: any, scene: Scene, rootUrl: string): SkyMaterial;
    }
}
