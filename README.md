
# Tug SEO Bundle

The Tug SEO Bundle is a Symfony bundle that provides a powerful and flexible solution for managing SEO metadata in your 
Symfony applications. With this bundle, you can easily define and manage SEO fields, their translations, and rendering 
options, helping you optimize your website's search engine visibility.

## Features

- Define SEO fields for various parts of your application, such as titles, descriptions, keywords, and more.
- Easily manage translations for SEO fields, allowing you to serve content in multiple languages.
- Customize SEO metadata rendering options to suit your needs.
- Integration with Symfony's Twig templating engine for easy rendering of SEO metadata in your templates.
- Extensible architecture that allows you to add custom SEO fields and renderers.
- Built-in support for common SEO standards, including Open Graph and Twitter Card.

## Installation

You can install the Tug SEO Bundle using Composer:

```bash
composer require tugrul/seo-bundle
```

After installation, make sure to configure the bundle as needed for your project.

## Configuration

The Tug SEO Bundle provides a wide range of configuration options to tailor SEO metadata management to your requirements. 
You can configure SEO fields, renderers, translation settings, and more. Check the [Configuration](#configuration) 
section in the documentation for detailed information on available options.

### Basic Configuration

Create a new `config\packages\tug_seo.yaml` file then put contents below.

```yaml
tug_seo:
  hierarchy:
    index:
      - information
      - gallery
    information:
      - about_us
      - faq
  default:
    title: My Website
    description: If there is no description of the route name, this description will appear on every page
  routes:
    index:
      title: Index Page
      description: This is homepage of my website
    gallery:
      title: Gallery
      description: Photos of staff
    information:
      title: Information
      description: Some information about my business
    about_us:
      title: About Us
      description: We are responsible for special assignments
    faq:
      title: Frequently Asked Questions
      description: Here are our short answers to the most frequently asked questions
```

## Usage

Using the Tug SEO Bundle in your Symfony application is straightforward. Here are the basic steps:

1. Define SEO fields: Use the provided services to define SEO fields for your application, such as titles, descriptions, and keywords.
2. Configure translations: If your application serves content in multiple languages, configure translations for SEO fields.
3. Render SEO metadata: In your templates, use the Twig extension to render SEO metadata for the current route.
4. Customize rendering: Customize the rendering of SEO metadata according to your needs, such as adding Open Graph tags or Twitter Card tags.
5. Extend functionality: If necessary, extend the bundle's functionality by adding custom SEO fields or renderers.

## Registering Services (Optional)

While the Tug SEO Bundle provides a comprehensive set of services out of the box, you have the option to register only 
the services you need for your specific use case. Registering services is an opt-in feature for users who want to 
customize or use specific parts of the bundle code. See the [Registering Services](#registering-services-optional) 
section below for more details.

### Define your own field type

Write your definition code for custom field.
```php
namespace App\MyProject\Field;

use Tug\SeoBundle\Field\{FieldData, FieldInterface};
use Tug\SeoBundle\Model\Meta as MetaModel;

class Robot implements FieldInterface
{
    /**
     * @inheritDoc
     */
    public function getNamespace(): array
    {
        return ['my', 'robot'];
    }

    public function buildModels(FieldData $fieldData): iterable
    {
        $meta = new MetaModel();
        
        $meta->setName('robot');
        $meta->setContent($fieldData->getContent());
        
        yield $meta;
    }
}
```

Enable it on service configuration for autowiring.
```yaml
services:
  App\MyProject\Field\Robot: ~
```

Configure it on your `tug_seo.yaml` file.
```yaml
tug_seo:
  routes:
    my_special_route:
      my:
        robot: noindex, nofollow
```

You will see the code below on your generated html.
```html
<meta name="robots" content="noindex, nofollow">
```

## License

The Tug SEO Bundle is open-source software licensed under the [MIT License](LICENSE).

## Contributing

Contributions are welcome! If you have suggestions, bug reports, or would like to contribute to the development of 
this bundle, please follow our [Contribution Guidelines](CONTRIBUTING.md).

## Credits

The Tug SEO Bundle is developed and maintained by the Tuğrul.

Special thanks to our contributors for their valuable input and contributions.

## Support

If you encounter any issues or have questions about the Tug SEO Bundle, 
please [open an issue](https://github.com/tugrul/SeoBundle/issues) on GitHub, and we'll do our best to assist you.

