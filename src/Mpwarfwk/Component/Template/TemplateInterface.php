<?php

namespace Mpwarfwk\Component\Template;

interface TemplateInterface {
    public function renderView($view, array $params);
}