# Remote repository for WPML language configuration files

This is the repository that the WPML plugin uses to keep the list of language configuration files always up to date.

## Language configuration files

Each plugin / theme can have a language configuration file that will tell WPML how to translate its data. You have more information about creating language configuration files over here:

https://wpml.org/documentation/support/language-configuration-files/

Language configuration files can be stored directly in the root folder of the plugin / theme where they belong or they can be added to this repository. The WPML plugin will periodically read this repository to keep up to date.

## Validating XML files

With `xmllint` and `wpml-config.xsd` we can validate XML files:

* Instructions for installing `xmllint` will vary according to your OS. A quick Google search should help you.
* The `wpml-config.xsd` file can be found in the `res/xsd` folder of the main WPML plugin.

```
xmllint --schema wpml-config.xsd --noout <path-to-wpml-config.xml>
```

## Repository structure

The language configuration repository has:

- An index file to keep items organized under plugins and themes: [config-index.xml](config-index.xml)
- A folder for each entry in the index file with a `wpml-config.xml` file inside.

## Index file format

There are a couple of requisites:

- The XML entry has to be inside one of these elements: `<plugins>` or `<themes>`
- The id attribute in the index file corresponds to the folder name and must match the folder name of the plugin or theme.
- The name of the plugin has to match the name of the plugin in its header.

For example:

`<item id="sitepress-multilingual-cms" override_local="true">WPML Multilingual CMS</item>`

## Contributing

- Fork the project or create a new branch
- Make your changes, test them and push
- Create a merge request for your branch to be merged into master
- The changes will be reviewed and, if approved, merged
