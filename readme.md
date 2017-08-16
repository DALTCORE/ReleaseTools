## Release tools for Projects

### Installation

Via composer:
```bash
composer global require daltcore/release-tools
```

Initialize ReleaseTools on current running repo. Creates .release-tools directory and .release-tool file.
```bash
release-tool init
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
release-tool changelog 'Changelog description' 1337 'Author Name' 
```

#### Releasing
In development you want to run the prepare command which will create a issue in the repo that is specified in the .release-tools file

```bash
release-tool release:prepare 1.0.0
```

Building changelog file 
```bash
release-tool build:changelog 1.0.0
```

#### .release-tools file
```yaml
 repo: group/repo
 api_url: https://gitlab.com
 api_key: AbC123DeF456Ghi789

```
