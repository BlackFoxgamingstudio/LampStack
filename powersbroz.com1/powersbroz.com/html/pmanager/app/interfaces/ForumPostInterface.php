<?php

interface ForumPostInterface {

    public function object();

    public function subject();

    public function body();

    public function author();

    public function is_reply();

    public function is_sticky();

    public function has_replies();

    public function get_replies();

    public function build_replies();

}