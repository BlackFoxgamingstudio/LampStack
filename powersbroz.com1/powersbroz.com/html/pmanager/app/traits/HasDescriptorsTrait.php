<?php

trait HasDescriptorsTrait {

    public function name() {
        return htmlspecialchars($this->name);
    }

    public function desc() {
        return nl2br(htmlspecialchars($this->description));
    }

}