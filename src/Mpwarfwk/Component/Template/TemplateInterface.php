<?php

namespace Mpwarfwk\Component\Template;

interface TemplateInterface {
    public function render($template, array $params);
}