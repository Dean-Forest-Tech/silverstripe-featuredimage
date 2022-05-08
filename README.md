# SilverStripe Featured Image

Adds generic featured image to defined DataObjects and adds some extra helper methods 

## Instalation

Install via composer:

    composer require "i-lateral/silverstripe-featuredimage"

## Setup

First off you need to map the extension to the object you want to add
featured images to. You can do this via YML config:

    Path\To\My\Object:
        extensions:
            - ilateral\SilverStripe\FeaturedImage\ObjectExtension

**NOTE** By default, this module adds a featured image to `SiteTree ` (if the
CMS is installed).

## Usage

By default this module adds a field `FeaturedImage` to the CMS for your
extended objects. This module also adds some simple helper methods:

`Object::getFeaturedImagesFromHierachy()`: Get a list of images, first from
the current object and any parents, grandparents, etc. If the object
doesn't support hierachy, then only a list with one item is returned.

`Object::getFeaturedImagesFromDescendants()`: Get a list of images, first from
the current object and any parents, grandparents, etc. If the object
doesn't support hierachy, then only a list with one item is returned.

## Blog Module

If the blog module is installed, this module will copy featured image ID's into
the new FeaturedImageID on `SiteTree`