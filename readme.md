## Release tools for Projects

### Installation

Via composer:
```bash
composer global require daltcore/release-tools
```

or  
Via executable:
[Go to releases](https://github.com/DALTCORE/ReleaseTools/releases/latest) and add this executable to your $PATH environment.

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

_If a error occurs then you can add the `-vvv` parameter to the tool. A stack-trace will be shown.

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

#### Building a stub:  
If you need to create a custom stub for `release-tool prepare` you can  
create a file called `prepare.stub` in the directory `.release-tools/stubs`  

These files are markdown files and the following variables are available:
- :version This represents the version you fill when askes
- :reop This represents the `repo` variable from the `.release-tool` file

This wil override as the issue template pushed to GitLab when releasing.

Example:
```markdown
**Release `:repo` version `:version`**

*Pre flight checks*
* [ ] - Notify in `5_Releases`
* [ ] - A
* [ ] - B
  * [ ] - C

```

#### Building playbooks:
If you need to create a custom playbook for `release-tool playbook <playbook-name>` you can  
create a file called `<name>.rtp` in the directory `.release-tools/playbooks`

These files are yaml files and the following variables are available:
- :version This represents the version you fill when askes
- :reop This represents the `repo` variable from the `.release-tool` file
 
Example:
```yaml
playbook:
  gitlab:
    make_branch:
      from: develop
      to: releases/:version

    tag:
      from: releases/:version
      version: v:version

    merge_request:
      branches: releases/:version > master

    merge_request:
      branches: master > develop

  mattermost:
    notify:
      channel: 'releases'
      message: '@channel We''re about to release version :version of the project :repo. Please stop merging now into develop until next announcement'

```
 
The example contains all available methods for playbooks. 

#### .release-tools file example
```yaml
repo: group/repo
api_url: https://gitlab.com
api_key: AbC123DeF456Ghi789
mattermost_webhook: https://mattermost.server.com/hooks/hook-uri
```
