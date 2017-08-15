## Release tools for Projects

### Installation

Via composer:
```bash
composer install daltcore/release-tools
```

### Usage

#### Development  
In development you should make a new branch for all your features/changes.   
Release tools makes use of the current branch name for file naming for the changelog files.

Adding a new changelog file to the repo  
`Changelog description` = A small description for the changelog   
 `53` = Merge request id  
`Author Name` = Your name; You made the merge request  (optional; the git author will be taken)
```bash
php vendor/bin/release changelog 'Changelog description' '53' 'Author Name' 
```

#### Releasing
In development you want to run the prepare command which will create a issue in the repo that is specified in the .release-tools file

```bash
php vendor/bin/release prepare 1.0.0
```

#### .release-tools file
```yaml
 repo: group/repo
 api_url: https://gitlab.com
 api_key: AbC123DeF456Ghi789

```
