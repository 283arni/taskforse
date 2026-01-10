<?php
require_once "../vendor/autoload.php";

use taskforce\classes\AvailableActions;

$strategy = new AvailableActions('new', 1);

assert($strategy->getNextStatus('new') == AvailableActions::STATUS_CANCEL, 'sad');
