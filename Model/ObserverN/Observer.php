<?php

require_once 'subject.php';

interface Observer {
    public function update(Subject $subject);
}

