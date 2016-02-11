﻿module BABYLON.EDITOR.GUI {
    export class GUIWindow extends GUIElement implements IGUIWindowElement {
        // Public members
        public title: string = "";
        public body: string = "";
        public size: Vector2 = new Vector2(800, 600);
        public buttons: Array<string> = new Array<string>();
        public modal: boolean = true;
        public showClose: boolean = true;
        public showMax: boolean = true;

        // Private members
        private _onCloseCallbacks: Array<() => void> = new Array<() => void>();
        private _onCloseCallback: () => void;
        private _onToggle: (maximized: boolean, width: number, height: number) => void;

        /**
        * Constructor
        * @param name: the form name
        */
        constructor(name: string, core: EditorCore, title: string, body: string, size?: Vector2, buttons?: Array<string>) {
            super(name, core);

            // Initialize
            this.title = title;
            this.body = body;

            if (size)
                this.size = size;

            if (buttons)
                this.buttons = buttons;

            this._onCloseCallback = () => {
                this.core.editor.renderMainScene = true;

                for (var i = 0; i < this._onCloseCallbacks.length; i++) {
                    this._onCloseCallbacks[i]();
                }
            };
        }

        // Destroy the element (W2UI)
        public destroy(): void {
            (<W2UI.IWindowElement>this.element).clear();
        }

        // Sets the on close callback
        public setOnCloseCallback(callback: () => void): void {
            this._onCloseCallbacks.push(callback);
        }

        // Closes the window
        public close(): void {
            (<W2UI.IWindowElement>this.element).close();
        }

        // Maximizes the window
        public maximize(): void {
            (<W2UI.IWindowElement>this.element).max();
        }

        // Toggle callback
        public set onToggle(callback: (maximized: boolean, width: number, height: number) => void) {
            var window = <W2UI.IWindowElement>this.element;
            var windowEvent = (event: any) => {
                event.onComplete = (eventData) => {
                    callback(eventData.options.maximized, eventData.options.width, eventData.options.height);
                };
            };

            window.onMax = windowEvent;
            window.onMin = windowEvent;

            this._onToggle = callback;
        }

        // Toggle callback
        public get onToggle(): (maximized: boolean, width: number, height: number) => void {
            return this._onToggle;
        }

        // Notify a message
        public notify(message: string): void {
            w2popup.message({
                width: 400,
                height: 180,
                html: "<div style=\"padding: 60px; text-align: center\">" + message + "</div>\"" +
                      "<div style=\"text- align: center\"><button class=\"btn\" onclick=\"w2popup.message()\">Close</button>"
            });
        }

        // Build element
        public buildElement(parent: string): void {
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
                element.click((result: JQueryEventObject) => {
                    var ev = new Event();
                    ev.eventType = EventType.GUI_EVENT;
                    ev.guiEvent = new GUIEvent(this, GUIEventType.WINDOW_BUTTON_CLICKED, result.target.id.replace(buttonID, ""));
                    this.core.sendEvent(ev);
                });
            }

            // Configure window
            var window = <W2UI.IWindowElement>this.element;
            window.onClose = this._onCloseCallback;

            // Configure editor
            this.core.editor.renderMainScene = false;
        }
    }
}