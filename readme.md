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

Check if ReleaseTools is ready for use
```bash
release-tool status
```

_If you want to know more about thestructure behind ReleaseTools  
then you can use the `-v` flag behind every command to see the  
verbose info_

### Usage

#### Development  
In development you should make a new branch for all your features/changes.   
Release tools makes use of the current branch name for file naming for the changelog files.

Adding a new changelog file to the repo, this command is interactive.  
_For testing you can use `--dry-run` as parameter_
```bash
release-tool changelog 
```

Implement git hook to force you to make a changelog entry
```bash
release-tool hooks:made-changelog
```

#### Releasing
In development you want to run the prepare command which will create a issue in the repo that is specified in the .release-tools file  
_For testing you can use `--dry-run` as parameter_
```bash
release-tool release:prepare
```

Building changelog file   
_For testing you can use `--dry-run` as parameter_
```bash
release-tool build:changelog
```
List all pending changelogs
```bash
release-tool list:changelog
``` 
```text
+-------------------+------------+---------------+--------------+
| Title             | Author     | Merge Request | Type         |
+-------------------+------------+---------------+--------------+
| this is a test MR | Ramon Smit | 1             | Security fix |
+-------------------+------------+---------------+--------------+
```

#### .release-tools file
```yaml
 repo: group/repo
 api_url: https://gitlab.com
 api_key: AbC123DeF456Ghi789

```
