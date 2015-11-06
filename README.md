Yahoo Weather API
=================

This [Symfony](http://symfony.com/) bundle providing base for manage contact form.

The aim is to factorise website contact form.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/8b9d7aff-9d73-4a54-8c57-edc2257a24ab/mini.png)](https://insight.sensiolabs.com/projects/8b9d7aff-9d73-4a54-8c57-edc2257a24ab) [![Latest Stable Version](https://poser.pugx.org/th3mouk/contact-bundle/v/stable)](https://packagist.org/packages/th3mouk/contact-bundle) [![Total Downloads](https://poser.pugx.org/th3mouk/contact-bundle/downloads)](https://packagist.org/packages/th3mouk/contact-bundle) [![Build Status](https://travis-ci.org/Th3Mouk/ContactBundle.svg?branch=master)](https://travis-ci.org/Th3Mouk/ContactBundle) [![Latest Unstable Version](https://poser.pugx.org/th3mouk/contact-bundle/v/unstable)](https://packagist.org/packages/th3mouk/contact-bundle) [![License](https://poser.pugx.org/th3mouk/contact-bundle/license)](https://packagist.org/packages/th3mouk/contact-bundle)


## Installation

`php composer.phar require th3mouk/contact-bundle ^1.1`

Add to the `appKernel.php`:

```php
new Th3Mouk\ContactBundle\Th3MoukContactBundle(),
```

Update your `routing.yml` application's file.

```yml
th3mouk_contact:
    resource: "@Th3MoukContactBundle/Resources/config/routing.yml"
    prefix:   /contact
```

Configure entities and templates in `config.yml`

```yml
th3mouk_contact:
    datas:
        from: noreply@domain.com
        to:
            - test.mail@domain.com
        subject: Contact request from your website
            
    class:
        entity: AppBundle\Entity\Contact
        formType: AppBundle\Form\ContactType

    templates:
        application: AppBundle:Contact:contact.html.twig
        mailer: AppBundle:Contact:mail.html.twig
```

## Usage

Create `Contact` entity that implement the `Th3Mouk\ContactBundle\Entity\ContactInterface` with the `app/console d:g:entity`.

Generate the relative FormType: `app/console d:g:f AppBundle:Contact`.

Create two template for frontend and mail, you have access to `form` object to draw your form, and your `contact` object in the mail template.

Check the following exemples:

### Exemple of ContactType

```php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('adress')
            ->add('zipCode')
            ->add('city')
            ->add('phone')
            ->add('email', 'email')
            ->add('message')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_contact_type';
    }
}
```

### Exemple of Form Template

```twig
<h1>Contact Request</h1>

{{ form_start(form) }}

{{ form_row(form.name) }}
{{ form_row(form.adress) }}
{{ form_row(form.zipCode) }}
{{ form_row(form.city) }}
{{ form_row(form.phone) }}
{{ form_row(form.email) }}
{{ form_row(form.message) }}

<div class="form-group"><button type="submit" class="btn-default btn">Submit</button></div>

{{ form_rest(form) }}
{{ form_end(form) }}
```

### Exemple of Mail Template

```twig
{{ contact.name }}<br>
{{ contact.adress }}<br>
{{ contact.zipCode }}<br>
{{ contact.city }}<br>
{{ contact.phone }}<br>
{{ contact.email }}<br>
{{ contact.message }}
```

### Sonata Integration Exemple

First use the `app/console sonata:admin:generate` command.

Then add the service configuration:

```yml
app.admin.contact:
    class: AppBundle\Admin\ContactAdmin
    arguments: [~, AppBundle\Entity\Contact, SonataAdminBundle:CRUD]
    tags:
        - {name: sonata.admin, manager_type: orm, label: Contacts}
```

Add the admin group on the dashboard:

```yml
sonata.admin.group.contact:
    label:           Contact
    label_catalogue: SonataPageBundle
    icon:            '<i class="fa fa-envelope"></i>'
    items:
        - app.admin.contact
    roles: [ ROLE_ADMIN ]
```

Don't forget to add this group on a block:
```yml
sonata_admin:
    dashboard:
        blocks:
            - { position: left, type: sonata.admin.block.admin_list, settings: { groups: [...sonata.admin.group.contact...] }}
```

You're done! :+1:

## Events

You have access to two events before and after mail was send :
* [MailerEventsDefinition](https://github.com/Th3Mouk/ContactBundle/tree/master/Events/MailerEventsDefinition)

## Please

Feel free to improve this bundle.
