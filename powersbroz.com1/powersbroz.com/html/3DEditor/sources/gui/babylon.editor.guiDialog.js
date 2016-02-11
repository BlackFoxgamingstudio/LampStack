var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
var BABYLON;
(function (BABYLON) {
    var EDITOR;
    (function (EDITOR) {
        var GUI;
        (function (GUI) {
            var GUIDialog = (function (_super) {
                __extends(GUIDialog, _super);
                // Private members
                /**
                * Constructor
                * @param name: the form name
                */
                function GUIDialog(name, core, title, body) {
                    _super.call(this, name, core);
                    this.callback = null;
                    // Initialize
                    this.title = title;
                    this.body = body;
                }
                // Build element
                GUIDialog.prototype.buildElement = function (parent) {
                    var _this = this;
                    this.element = w2confirm(this.body, this.title, function (result) {
                        var ev = new EDITOR.Event();
                        ev.eventType = EDITOR.EventType.GUI_EVENT;
                        ev.guiEvent = new EDITOR.GUIEvent(_this, EDITOR.GUIEventType.UNKNOWN, result);
                        _this.core.sendEvent(ev);
                        if (_this.callback)
                            _this.callback(result);
                    });
                };
                return GUIDialog;
            })(GUI.GUIElement);
            GUI.GUIDialog = GUIDialog;
        })(GUI = EDITOR.GUI || (EDITOR.GUI = {}));
    })(EDITOR = BABYLON.EDITOR || (BABYLON.EDITOR = {}));
})(BABYLON || (BABYLON = {}));
//# sourceMappingURL=babylon.editor.guiDialog.js.map