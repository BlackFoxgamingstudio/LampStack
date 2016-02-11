var BABYLON;
(function (BABYLON) {
    var EDITOR;
    (function (EDITOR) {
        var SceneGraphTool = (function () {
            /**
            * Constructor
            * @param core: the editor core instance
            */
            function SceneGraphTool(core) {
                // Public members
                this.container = "BABYLON-EDITOR-SCENE-GRAPH-TOOL";
                this.sidebar = null;
                this.panel = null;
                this._graphRootName = "RootScene";
                this._menuDeleteId = "BABYLON-EDITOR-SCENE-GRAPH-TOOL-REMOVE";
                this._menuCloneId = "BABYLON-EDITOR-SCENE-GRAPH-TOOL-CLONE";
                // Initialize
                this._editor = core.editor;
                this._core = core;
                this.panel = this._editor.layouts.getPanelFromType("right");
                // Register this
                this._core.updates.push(this);
                this._core.eventReceivers.push(this);
            }
            // Pre update
            SceneGraphTool.prototype.onPreUpdate = function () {
            };
            // Post update
            SceneGraphTool.prototype.onPostUpdate = function () {
            };
            // Event
            SceneGraphTool.prototype.onEvent = function (event) {
                if (event.eventType === EDITOR.EventType.GUI_EVENT) {
                    if (event.guiEvent.caller === this.sidebar) {
                        if (event.guiEvent.eventType === EDITOR.GUIEventType.GRAPH_SELECTED) {
                            var ev = new EDITOR.Event();
                            ev.eventType = EDITOR.EventType.SCENE_EVENT;
                            ev.sceneEvent = new EDITOR.SceneEvent(event.guiEvent.data, EDITOR.SceneEventType.OBJECT_PICKED);
                            this._core.sendEvent(ev);
                            return true;
                        }
                        else if (event.guiEvent.eventType === EDITOR.GUIEventType.GRAPH_MENU_SELECTED) {
                            var id = event.guiEvent.data;
                            var object = this.sidebar.getSelectedData();
                            var scene = this._core.currentScene;
                            if (!object)
                                return false;
                            if (id === this._menuDeleteId) {
                                if (object && object.dispose && object !== this._core.camera) {
                                    var parent = object.parent;
                                    object.dispose();
                                    this.sidebar.removeNode(this.sidebar.getSelected());
                                    this.sidebar.refresh();
                                }
                                return true;
                            }
                            else if (id === this._menuCloneId) {
                                if (!(object instanceof BABYLON.Mesh))
                                    return true;
                                if (!object.geometry) {
                                    var emitter = object.clone(object.name + "Cloned", object.parent);
                                    EDITOR.Event.sendSceneEvent(emitter, EDITOR.SceneEventType.OBJECT_ADDED, this._core);
                                    EDITOR.Event.sendSceneEvent(emitter, EDITOR.SceneEventType.OBJECT_PICKED, this._core);
                                    this.sidebar.setSelected(emitter.id);
                                    var buffer = null;
                                    for (var i = 0; i < scene.particleSystems.length; i++) {
                                        if (scene.particleSystems[i].emitter === object) {
                                            buffer = scene.particleSystems[i].particleTexture._buffer;
                                        }
                                        else if (scene.particleSystems[i].emitter === emitter) {
                                            scene.particleSystems[i].particleTexture = BABYLON.Texture.CreateFromBase64String(buffer, scene.particleSystems[i].particleTexture.name + "Cloned", scene);
                                            break;
                                        }
                                    }
                                }
                                return true;
                            }
                        }
                    }
                }
                else if (event.eventType === EDITOR.EventType.SCENE_EVENT) {
                    if (event.sceneEvent.eventType === EDITOR.SceneEventType.OBJECT_ADDED) {
                        var object = event.sceneEvent.object;
                        if (object instanceof BABYLON.ReflectionProbe) {
                            var rpNode = this.sidebar.createNode(object.name + this._core.currentScene.reflectionProbes.length, object.name, "icon-effects", object);
                            this.sidebar.addNodes(rpNode, this._graphRootName + "TARGETS");
                        }
                        else if (object instanceof BABYLON.RenderTargetTexture) {
                            var rpNode = this.sidebar.createNode(object.name + this._core.currentScene.customRenderTargets.length, object.name, "icon-camera", object);
                            this.sidebar.addNodes(rpNode, this._graphRootName + "TARGETS");
                        }
                        else
                            this._modifyElement(event.sceneEvent.object, null);
                        return false;
                    }
                    else if (event.sceneEvent.eventType === EDITOR.SceneEventType.OBJECT_REMOVED) {
                        this.sidebar.removeNode(event.sceneEvent.object.id);
                        this.sidebar.refresh();
                        return false;
                    }
                }
                return false;
            };
            // Fills the graph of nodes (meshes, lights, cameras, etc.)
            SceneGraphTool.prototype.fillGraph = function (node, graphNodeID) {
                var children = null;
                var root = null;
                var scene = this._core.currentScene;
                if (!graphNodeID) {
                    this.sidebar.clear();
                    // Add root
                    var rootNode = this.sidebar.createNode(this._graphRootName, "Scene", "icon-scene", this._core.currentScene);
                    this.sidebar.addNodes(rootNode);
                    root = this._graphRootName;
                    // Reflection probes
                    var rpNode = this.sidebar.createNode(this._graphRootName + "TARGETS", "Render Targets", "icon-folder");
                    this.sidebar.addNodes(rpNode, this._graphRootName);
                    for (var i = 0; i < scene.reflectionProbes.length; i++) {
                        var rp = scene.reflectionProbes[i];
                        this.sidebar.addNodes(this.sidebar.createNode(rp.name + i, rp.name, "icon-effects", rp), rpNode.id);
                    }
                    for (var i = 0; i < scene.customRenderTargets.length; i++) {
                        var rt = scene.customRenderTargets[i];
                        this.sidebar.addNodes(this.sidebar.createNode(rt.name + i, rp.name, "icon-camera", rp), rpNode.id);
                    }
                    // Audio
                    var audioNode = this.sidebar.createNode(this._graphRootName + "AUDIO", "Audio", "icon-folder");
                    this.sidebar.addNodes(audioNode, this._graphRootName);
                    for (var i = 0; i < scene.soundTracks.length; i++) {
                        var soundTrack = scene.soundTracks[i];
                        var soundTrackNode = this.sidebar.createNode("Soundtrack " + soundTrack.id, "Soundtrack " + soundTrack.id, "icon-sound", soundTrack);
                        soundTrackNode.count = soundTrack.soundCollection.length;
                        this.sidebar.addNodes(soundTrackNode, audioNode.id);
                        for (var j = 0; j < soundTrack.soundCollection.length; j++) {
                            var sound = soundTrack.soundCollection[j];
                            this.sidebar.addNodes(this.sidebar.createNode("Sound" + j, sound.name, "icon-sound", sound), soundTrackNode.id);
                        }
                    }
                }
                if (!node) {
                    children = [];
                    this._getRootNodes(children, "meshes");
                    this._getRootNodes(children, "lights");
                    this._getRootNodes(children, "cameras");
                }
                else
                    children = node.getDescendants ? node.getDescendants() : [];
                if (root === this._graphRootName)
                    this.sidebar.setNodeExpanded(root, true);
                // If submeshes
                if (node instanceof BABYLON.AbstractMesh && node.subMeshes && node.subMeshes.length > 1) {
                    var subMeshesNode = this.sidebar.createNode(node.id + "SubMeshes", "Sub-Meshes", "icon-mesh", node);
                    subMeshesNode.count = node.subMeshes.length;
                    this.sidebar.addNodes(subMeshesNode, node.id);
                    for (var i = 0; i < node.subMeshes.length; i++) {
                        var subMesh = node.subMeshes[i];
                        var subMeshNode = this.sidebar.createNode(node.id + "SubMesh" + i, subMesh.getMaterial().name, "icon-mesh", subMesh);
                        this.sidebar.addNodes(subMeshNode, subMeshesNode.id);
                    }
                }
                // If children, then fill the graph recursively
                if (children !== null) {
                    // Set elements before
                    for (var i = 0; i < children.length; i++) {
                        var object = children[i];
                        var childrenLength = object.getDescendants().length;
                        var icon = this._getObjectIcon(object);
                        var childNode = this.sidebar.createNode(object.id, object.name, icon, object);
                        if (childrenLength > 0)
                            childNode.count = childrenLength;
                        this.sidebar.addNodes(childNode, root ? root : node.id);
                        this.fillGraph(object, object.id);
                    }
                }
            };
            // Creates the UI
            SceneGraphTool.prototype.createUI = function () {
                if (this.sidebar != null)
                    this.sidebar.destroy();
                this.sidebar = new EDITOR.GUI.GUIGraph(this.container, this._core);
                // Set menus
                this.sidebar.addMenu(this._menuDeleteId, "Remove", "icon-error");
                this.sidebar.addMenu(this._menuCloneId, "Clone", "icon-clone");
                // Build element
                this.sidebar.buildElement(this.container);
                /// Default node
                var node = this.sidebar.createNode(this._graphRootName, "Scene", "icon-scene", this._core.currentScene);
                this.sidebar.addNodes(node);
            };
            // Fills the result array of nodes when the node hasn't any parent
            SceneGraphTool.prototype._getRootNodes = function (result, entities) {
                var elements = this._core.currentScene[entities];
                for (var i = 0; i < elements.length; i++) {
                    if (!elements[i].parent) {
                        result.push(elements[i]);
                    }
                }
            };
            // Returns the appropriate icon of the node (mesh, animated mesh, light, camera, etc.)
            SceneGraphTool.prototype._getObjectIcon = function (node) {
                if (node instanceof BABYLON.Mesh) {
                    // Check particles
                    if (!node.geometry) {
                        var scene = node.getScene();
                        for (var i = 0; i < scene.particleSystems.length; i++) {
                            if (scene.particleSystems[i].emitter === node)
                                return "icon-particles";
                        }
                    }
                    // Else...
                    if (node.skeleton)
                        return "icon-animated-mesh";
                    return "icon-mesh";
                }
                else if (node instanceof BABYLON.SubMesh) {
                    return "icon-mesh";
                }
                else if (node instanceof BABYLON.Light) {
                    if (node instanceof BABYLON.DirectionalLight)
                        return "icon-directional-light";
                    else
                        return "icon-light";
                }
                else if (node instanceof BABYLON.Camera) {
                    return "icon-camera";
                }
                else if (node instanceof BABYLON.Sound) {
                    return "icon-sound";
                }
                return "";
            };
            // Removes or adds a node from/to the graph
            SceneGraphTool.prototype._modifyElement = function (node, parentNode) {
                if (!node)
                    return;
                // Add node
                var icon = this._getObjectIcon(node);
                this.sidebar.addNodes(this.sidebar.createNode(node.id, node.name, icon, node), parentNode ? parentNode.id : this._graphRootName);
                this.sidebar.refresh();
            };
            return SceneGraphTool;
        })();
        EDITOR.SceneGraphTool = SceneGraphTool;
    })(EDITOR = BABYLON.EDITOR || (BABYLON.EDITOR = {}));
})(BABYLON || (BABYLON = {}));
//# sourceMappingURL=babylon.editor.sceneGraphTool.js.map