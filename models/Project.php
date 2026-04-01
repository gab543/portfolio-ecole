<?php

namespace Models;

class Project {
    public ?int $id = null;
    public string $title = '';
    public string $description = '';
    public string $start_date = '';
    public string $end_date = '';
    public string $labels = '';
    public string $link = '';
    public ?int $id_category = null;
}
