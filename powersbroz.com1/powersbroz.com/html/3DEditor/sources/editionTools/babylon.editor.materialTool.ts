﻿module BABYLON.EDITOR {
    export class MaterialTool extends AbstractDatTool {
        // Public members
        public object: Node = null;

        public tab: string = "MATERIAL.TAB";

        // Private members
        private _forbiddenElements: Array<string>;
        private _dummyProperty: any = "";

        /**
        * Constructor
        * @param editionTool: edition tool instance
        */
        constructor(editionTool: EditionTool) {
            super(editionTool);

            // Initialize
            this.containers = [
                "BABYLON-EDITOR-EDITION-TOOL-MATERIAL"
            ];

            this._forbiddenElements = [
                "pointSize",
                "sideOrientation",
                "alphaMode",
                "zOffset",
                "fillMode",

                // PBR
                "overloadedAmbientIntensity",
                "overloadedDiffuseIntensity",
                "overloadedSpecularIntensity",
                "overloadedEmissiveIntensity",
                "overloadedAmbient",
                "overloadedDiffuse",
                "overloadedSpecular",
                "overloadedEmissive",
                "overloadedReflection",
                "overloadedGlossiness",
                "overloadedGlossinessIntensity",
                "overloadedReflectionIntensity",
                "overloadedShadowIntensity",
                "overloadedShadeIntensity",
            ];
        }

        // Object supported
        public isObjectSupported(object: any): boolean {
            if (object instanceof Mesh) {
                if (object.material && !(object.material instanceof MultiMaterial))
                    return true;
            }
            else if (object instanceof SubMesh) {
                var subMesh = <SubMesh>object;
                var multiMaterial = <MultiMaterial>subMesh.getMesh().material;
                if (multiMaterial instanceof MultiMaterial && multiMaterial.subMaterials[subMesh.materialIndex])
                    return true;
            }

            return false;
        }

        // Creates the UI
        public createUI(): void {
            // Tabs
            this._editionTool.panel.createTab({ id: this.tab, caption: "Material" });
        }

        // Update
        public update(): void {
            var object: any = this._editionTool.object;
            var scene = this._editionTool.core.currentScene;

            super.update();

            if (object instanceof AbstractMesh) {
                object = object.material;
            }
            else if (object instanceof SubMesh) {
                object = object.getMaterial();
            }

            if (!object || !(object instanceof Material))
                return;

            this._element = new GUI.GUIEditForm(this.containers[0], this._editionTool.core);
            this._element.buildElement(this.containers[0]);
            this._element.remember(object);

            // Material
            var materialFolder = this._element.addFolder("Material");

            var materials: string[] = [];
            for (var i = 0; i < scene.materials.length; i++)
                materials.push(scene.materials[i].name);

            this._dummyProperty = object.name;
            materialFolder.add(this, "_dummyProperty", materials).name("Material :").onFinishChange((result: any) => {
                var material = scene.getMaterialByName(result);
                this._editionTool.object.material = material;
                this.update();
            });

            if (object instanceof StandardMaterial) {
                materialFolder.add(this, "_convertToPBR").name("Convert to PBR");
            }

            // Values
            var generalFolder = this._element.addFolder("Common");
            generalFolder.add(object, "name").name("Name");

            var propertiesFolder = this._element.addFolder("Properties");
            this._addNumberFields(propertiesFolder, object);
            this._addBooleanFields(propertiesFolder, object);

            var colorsFolder = this._element.addFolder("Colors");
            this._addColorFields(colorsFolder, object);

            var vectorsFolder = this._element.addFolder("Vectors");
            this._addVectorFields(vectorsFolder, object);
        }

        // Beautify property name
        private _beautifyName(name: string): string {
            var result = name[0].toUpperCase();// + name.substring(1, name.length);

            for (var i = 1; i < name.length; i++) {
                var char = name[i];

                if (char === char.toUpperCase())
                    result += " ";

                result += name[i];
            }

            return result;
        }

        // Adds a number
        private _addNumberFields(folder: dat.IFolderElement, object: any): void {
            for (var thing in object) {
                var value = object[thing];
                if (typeof value === "number" && thing[0] !== "_" && this._forbiddenElements.indexOf(thing) === -1) {
                    var item = folder.add(object, thing);

                    this._element.tagObjectIfChanged(item, object, thing);

                    if (thing === "alpha") {
                        item.min(0.0).max(1.0).step(0.01);
                    }

                    item.step(0.01).name(this._beautifyName(thing));
                }
            }
        }

        // Adds booleans
        private _addBooleanFields(folder: dat.IFolderElement, object: any): void {
            for (var thing in object) {
                var value = object[thing];
                if (typeof value === "boolean" && thing[0] !== "_" && this._forbiddenElements.indexOf(thing) === -1) {
                    var item = folder.add(object, thing).name(this._beautifyName(thing));

                    this._element.tagObjectIfChanged(item, object, thing);
                }
            }
        }

        // Adds colors
        private _addColorFields(folder: dat.IFolderElement, object: any): void {
            for (var thing in object) {
                var value = object[thing];
                if (value instanceof Color3 && thing[0] !== "_" && this._forbiddenElements.indexOf(thing) === -1) {
                    var colorFolder = this._element.addFolder(this._beautifyName(thing), folder);
                    colorFolder.close();
                    colorFolder.add(object[thing], "r").name("r").min(0.0).max(1.0).step(0.001);
                    colorFolder.add(object[thing], "g").name("g").min(0.0).max(1.0).step(0.001);
                    colorFolder.add(object[thing], "b").name("b").min(0.0).max(1.0).step(0.001);
                }
            }
        }

        // Adds vectors
        private _addVectorFields(folder: dat.IFolderElement, object: any): void {
            for (var thing in object) {
                var value = object[thing];

                if (thing[0] === "_" || this._forbiddenElements.indexOf(thing) === -1)
                    continue;

                if (value instanceof Vector3 || value instanceof Vector2) {
                    var vectorFolder = this._element.addFolder(this._beautifyName(thing), folder);
                    vectorFolder.close();
                    vectorFolder.add(object[thing], "x").name("x").step(0.01);
                    vectorFolder.add(object[thing], "y").name("y").step(0.01);

                    if (value instanceof Vector3)
                        vectorFolder.add(object[thing], "z").name("z").step(0.01);
                }
            }
        }

        // Converts a standard material to PBR
        private _convertToPBR(): void {
            var object: StandardMaterial = null;
            var mesh = this._editionTool.object;

            if (mesh instanceof AbstractMesh) {
                object = mesh.material;
            }
            else if (mesh instanceof SubMesh) {
                object = mesh.getMaterial();
            }

            if (!object)
                return;

            var scene = this._editionTool.core.currentScene;
            var pbr: PBRMaterial = new PBRMaterial("New PBR Material", scene);

            // Textures
            pbr.diffuseTexture = object.diffuseTexture;
            pbr.bumpTexture = object.bumpTexture;
            pbr.ambientTexture = object.ambientTexture;
            pbr.emissiveTexture = object.emissiveTexture;
            pbr.lightmapTexture = object.lightmapTexture;
            pbr.reflectionTexture = object.reflectionTexture || scene.reflectionProbes[0].cubeTexture;
            pbr.specularTexture = object.specularTexture;
            pbr.useAlphaFromDiffuseTexture = object.useAlphaFromDiffuseTexture;

            // Colors
            pbr.diffuseColor = object.diffuseColor;
            pbr.emissiveColor = object.emissiveColor;
            pbr.specularColor = object.specularColor;
            pbr.ambientColor = object.ambientColor;
            pbr.glossiness = object.specularPower;
            pbr.alpha = object.alpha;
            pbr.alphaMode = object.alphaMode;

            // Finish
            if (mesh instanceof AbstractMesh) {
                mesh.material = pbr;
            }
            else if (mesh instanceof SubMesh) {
                var subMesh = <SubMesh>mesh;
                var material = subMesh.getMesh().material;

                if (material instanceof MultiMaterial) {
                    var materialIndex = material.subMaterials.indexOf(subMesh.getMaterial());

                    if (materialIndex !== -1)
                        material.subMaterials[materialIndex] = pbr;
                }
            }

            this.update();
        }
    }
}