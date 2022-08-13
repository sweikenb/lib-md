# Markdown library

## Add GIT commit hook

Execute in project root:

```bash
rm -f "$(pwd)/.git/hooks/pre-commit"
ln -s "$(pwd)/bin/codequality" "$(pwd)/.git/hooks/pre-commit"
```
