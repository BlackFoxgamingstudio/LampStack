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
            var GUIWindow = (function (_super) {
                __extends(GUIWindow, _super);
                /**
                * Constructor
                * @param name: the form name
                */
                function GUIWindow(name, core, title, body, size, buttons) {
                    var _this = this;
                    _super.call(this, name, core);
                    // Public members
                    this.title = "";
                    this.body = "";
                    this.size = new BABYLON.Vector2(800, 600);
                    this.buttons = new Array();
                    this.modal = true;
                    this.showClose = true;
                    this.showMax = true;
                    // Private members
                    this._onCloseCallbacks = new Array();
                    // Initialize
                    this.title = title;
                    this.body = body;
                    if (size)
                        this.size = size;
                    if (buttons)
                        this.buttons = buttons;
                    this._onCloseCallback = function () {
                        _this.core.editor.renderMainScene = true;
                        for (var i = 0; i < _this._onCloseCallbacks.length; i++) {
                            _this._onCloseCallbacks[i]();
                        }
                    };
                }
                // Destroy the element (W2UI)
                GUIWindow.prototype.destroy = function () {
                    this.element.clear();
                };
                // Sets the on close callback
                GUIWindow.prototype.setOnCloseCallback = function (callback) {
                    this._onCloseCallbacks.push(callback);
                };
                // Closes the window
                GUIWindow.prototype.close = function () {
                    this.element.close();
                };
                // Maximizes the window
                GUIWindow.prototype.maximize = function () {
                    this.element.max();
                };
                Object.defineProperty(GUIWindow.prototype, "onToggle", {
                    // Toggle callback
                    get: function () {
                        return this._onToggle;
                    },
                    // Toggle callback
                    set: function (callback) {
                        var window = this.element;
                        var windowEvent = function (event) {
                            event.onComplete = function (eventData) {
                                callback(eventData.options.maximized, eventData.options.width, eventData.options.height);
                            };
                        };
                        window.onMax = windowEvent;
                        window.onMin = windowEvent;
                        this._onToggle = callback;
                    },
                    enumerable: true,
                    configurable: true
                });
                // Notify a message
                GUIWindow.prototype.notify = function (message) {
                    w2popup.message({
                        width: 400,
                        height: 180,
                        html: "<div style=\"padding: 60px; text-align: center\">" + message + "</div>\"" +
                            "<div style=\"text- align: center\"><button class=\"btn\" onclick=\"w2popup.message()\">Close</button>"
                    });
                };
                // Build element
                GUIWindow.prototype.buildElement = function (parent) {
                    var _this = this;
                    // Create buttons
                    var buttonID = "WindowButton";
                    var buttons = "";
                    for (var i = 0; i < this.buttons.length; i++) {
                        buttons += "<button class=\"btn\" id=\"" + buttonID + this.buttons[i] + "\">" + this.buttons[i] + "</button>\n";
                    }
                    // Create window
                    this.element = w2popup.open({
                        title: this.title,
                        body: this.body,
                        buttons: buttons,
                        width: this.size.x,
                        height: this.size.y,
                        showClose: this.showClose,
                        showMax: this.showMax == null ? false : this.showMax,
                        modal: this.modal
                    });
                    // Create events for buttons
                    for (var i = 0; i < this.buttons.length; i++) {
                        var element = $("#" + buttonID + this.buttons[i]);
                        element.click(function (result) {
                            var ev = new EDITOR.Event();
                            ev.eventType = EDITOR.EventType.GUI_EVENT;
                            ev.guiEvent = new EDITOR.GUIEvent(_this, EDITOR.GUIEventType.WINDOW_BUTTON_CLICKED, result.target.id.replace(buttonID, ""));
                            _this.core.sendEvent(ev);
                        });
                    }
                    // Configure window
                    var window = this.element;
                    window.onClose = this._onCloseCallback;
                    // Configure editor
                    this.core.editor.renderMainScene = false;
                };
                return GUIWindow;
            })(GUI.GUIElement);
            GUI.GUIWindow = GUIWindow;
        })(GUI = EDITOR.GUI || (EDITOR.GUI = {}));
    })(EDITOR = BABYLON.EDITOR || (BABYLON.EDITOR = {}));
})(BABYLON || (BABYLON = {}));
//# sourceMappingURL=babylon.editor.guiWindow.js.map