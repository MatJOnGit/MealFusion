<?php

namespace Api\Utils;

class BodyUtils {
    private array $_bodyRequiredMethods = ['POST', 'PUT'];

    public bool $isBodyValid;
}