# Code Version Control
-----------------------

## Table of context
-----------------
 - [Preamble](#preamble)
 - [Git](#git)
 - [Branch Name](#branch-name)
 - [Merge](#merge)
 - [Commit Messages](#commit-messages)

### Preamble
-------------

The following is a set of conventions about code version control that strives to provide 
the best way to communicate enough context about every committed code change to fellow developers.

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", 
"RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as 
described in [RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

### Git
------

Git is official version control system that is used for majority of the company projects. 
It allows for easy distribution of the source code and keeps each change under version control 
(including the changes in local environment with future synchronization to the server repository).

### Branch Name
-----------------

Branch naming conventions:

 - Master - main repository stable branch
 - Develop - main repository develop branch
 - Release branches - created for each release to maintain different product versions. Example: 1.0, 1.1, 1.2, ..., 2, ...
 - Feature branches - can be created per feature (for implementation of specific JIRA story/task)
 and should match name pattern: feature/{feature_name}
 - Task branches - created for implementation of specific JIRA task/subtask. Pattern: task/{task_id}
 - Fix branches - can be created for group of fixes. Pattern: fix/{fix_name}

#### Examples of Branch Names

```
task/OK-17
task/OK-182_paypal
feature/autocomplete_demo
fix/OK-25
fix/forgot_password
1.0
1.1
1.0.1
```

### Merge
-------

Merge changes MUST be done using merge way instead of rebase.

### Merge-request & Pull-request
---------------------------------

Pull-request (PR) is a way to contribute changes to another branch and to perform code review. Pull request MUST have a name that will briefly describe what was implemented in scope of it and JIRA ticket ID. If the name is not enough, PR description should be used to provide more details.
After PR is merged and appropriate branch is not needed anymore - the branch MUST be deleted immediately.

### Commit Messages
-------------------

Every commit message MUST include:

- ticket id from bug tracking system
- short ticket description (summary)
- list of performed actions or changes in code (RECOMMENDED)

Examples:

```
<Task ID>: <Task summary>
- <action 1>
- <action 2>
....

OK-129: Generate ovpn configuration files
- updated migrations
- added SwiftMailer
- added tcp and upd extension
- add new field to user roles
```
