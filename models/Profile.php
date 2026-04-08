<?php

namespace Models;

class Profile {
    public string $full_name = '';
    public string $email = '';
    public string $phone_number = '';
    public string $description = '';
    public string $skills = '';
    public ?int $id_image = null;
    public ?string $image_url = null;
    public ?string $image_alt = null;
}
