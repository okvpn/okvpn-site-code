# Code Version Control

The following is a set of conventions about code version control that strives to provide the best way to communicate enough context about every committed code change to fellow developers.

### Git

Git is official version control system that is used for majority of the company projects. It allows for easy distribution of the source code and keeps each change under version control  (including the changes in local environment with future synchronization to the server repository).

### Branch Name

Branch naming conventions:

 - master - main repository stable branch
 - Release branches - created for each release to maintain different product versions. Example: 1.0, 1.1, 1.2, ..., 2, ...
 - Feature branches - can be created per feature and should match name pattern: feature/{feature_name}
 - Fix branches - can be created for group of fixes. Pattern: fix/{fix_name}
 - Task branches - created for implementation of specific JIRA task. Pattern: task/{task_id}

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

Merge changes MUST be done using merge way instead of rebase.

### Merage-request and Pull-request

Pull-request (PR) is a way to contribute changes to another branch and to perform code review. Pull request MUST have a name that will briefly describe what was implemented in scope of it and JIRA ticket ID. If the name is not enough, PR description should be used to provide more details.
After PR is merged and appropriate branch is not needed anymore - the branch MUST be deleted immediately.

### Commit Message

Every commit message MUST include:

- ticket id from bug tracking system
- short ticket description (summary)
- list of performed actions or changes in code 

Examples:

```
<Task ID>: <Task summary>
- <action 1>
- <action 2>
....
```
