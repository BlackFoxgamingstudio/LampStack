var BABYLON;
(function (BABYLON) {
    var EDITOR;
    (function (EDITOR) {
        var EditionTool = (function () {
            /**
            * Constructor
            * @param core: the editor core instance
            */
            function EditionTool(core) {
                // Public members
                this.object = null;
                this.container = "BABYLON-EDITOR-EDITION-TOOL";
                this.editionTools = new Array();
                this.panel = null;
                // Initialize
                this._editor = core.editor;
                this.core = core;
                this.panel = this._editor.layouts.getPanelFromType("left");
                // Register this
                this.core.updates.push(this);
                this.core.eventReceivers.push(this);
            }
            // Pre update
            EditionTool.prototype.onPreUpdate = function () {
            };
            // Post update
            EditionTool.prototype.onPostUpdate = function () {
            };
            // Event
            EditionTool.prototype.onEvent = function (event) {
                // GUI Event
                if (event.eventType === EDITOR.EventType.GUI_EVENT) {
                    if (event.guiEvent.eventType === EDITOR.GUIEventType.TAB_CHANGED && event.guiEvent.caller === this.panel) {
                        var tabID = event.guiEvent.data;
                        if (this._currentTab !== tabID) {
                            this._currentTab = tabID;
                            for (var i = 0; i < this.editionTools.length; i++) {
                                var tool = this.editionTools[i];
                                for (var j = 0; j < tool.containers.length; j++) {
                                    var element = $("#" + tool.containers[j]);
                                    if (tool.tab === this._currentTab) {
                                        element.show();
                                        tool.resize();
                                    }
                                    else {
                                        element.hide();
                                    }
                                }
                            }
                        }
                    }
                    else if (event.guiEvent.eventType === EDITOR.GUIEventType.LAYOUT_CHANGED) {
                        if (event.guiEvent.caller === this._editor.layouts) {
                            for (var i = 0; i < this.editionTools.length; i++) {
                                this.editionTools[i].resize();
                            }
                        }
                    }
                }
                // Scene Event
                if (event.eventType === EDITOR.EventType.SCENE_EVENT) {
                    if (event.sceneEvent.eventType === EDITOR.SceneEventType.OBJECT_PICKED) {
                        this.object = event.sceneEvent.object;
                        if (this.object !== null)
                            this.isObjectSupported(this.object);
                        return false;
                    }
                }
                return false;
            };
            // Object supported
            EditionTool.prototype.isObjectSupported = function (object) {
                var tabAlreadyShown = false;
                var supportedTools = [];
                for (var i = 0; i < this.editionTools.length; i++) {
                    var tool = this.editionTools[i];
                    var supported = tool.isObjectSupported(this.object);
                    if (supported) {
                        supportedTools.push(tool);
                        this.panel.showTab(tool.tab);
                        if (!tabAlreadyShown)
                            tabAlreadyShown = tool.tab === this._currentTab;
                    }
                    else {
                        for (var j = 0; j < tool.containers.length; j++) {
                            $("#" + tool.containers[j]).hide();
                            this.panel.hideTab(tool.tab);
                        }
                    }
                }
                // Activate tools
                for (var i = 0; i < supportedTools.length; i++) {
                    var tool = supportedTools[i];
                    if (!tabAlreadyShown) {
                        for (var j = 0; j < tool.containers.length; j++) {
                            $("#" + tool.containers[j]).show();
                        }
                        tabAlreadyShown = true;
                        this._currentTab = tool.tab;
                    }
                    else {
                    }
                    tool.update();
                }
                return false;
            };
            // Creates the UI
            EditionTool.prototype.createUI = function () {
                // Add default tools
                this.addTool(new EDITOR.GeneralTool(this));
                this.addTool(new EDITOR.MaterialTool(this));
                this.addTool(new EDITOR.SceneTool(this));
                this.addTool(new EDITOR.LightTool(this));
                this.addTool(new EDITOR.AnimationTool(this));
                this.addTool(new EDITOR.PostProcessesTool(this));
                this.addTool(new EDITOR.ReflectionProbeTool(this));
                this.addTool(new EDITOR.AudioTool(this));
                this.addTool(new EDITOR.ParticleSystemTool(this));
            };
            // Adds a tool
            EditionTool.prototype.addTool = function (tool) {
                var currentForm = this.container;
                $("#" + currentForm).append("<div id=\"" + tool.containers[0] + "\"></div>");
                $("#" + tool.containers[0]).hide();
                for (var i = 1; i < tool.containers.length; i++) {
                    $("#" + currentForm).after("<div id=\"" + tool.containers[i] + "\"></div>");
                    $("#" + tool.containers[i]).hide();
                    currentForm = tool.containers[i];
                }
                tool.createUI();
                this.editionTools.push(tool);
            };
            return EditionTool;
        })();
        EDITOR.EditionTool = EditionTool;
    })(EDITOR = BABYLON.EDITOR || (BABYLON.EDITOR = {}));
})(BABYLON || (BABYLON = {}));
//# sourceMappingURL=babylon.editor.editionTool.js.map