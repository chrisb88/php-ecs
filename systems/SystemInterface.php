<?php

namespace ecs\systems;

interface SystemInterface
{
    public function init();
    public function update();
}
