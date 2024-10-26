#!/bin/bash

# Clone the Git Runner into the target repo's .git/hooks directory
RUNNER_REPO="https://github.com/DonalWall207/sql-git-runner"
TARGET_REPO_PATH=$1

if [ -z "$TARGET_REPO_PATH" ]; then
    echo "Usage: ./install.sh <path_to_your_project>"
    exit 1
fi

# Clone the runner repo inside the hooks folder
git clone "$RUNNER_REPO" "$TARGET_REPO_PATH/.git/hooks/sql-git-runner"

# Create symlink for the pre-push hook
ln -s "$TARGET_REPO_PATH/.git/hooks/sql-git-runner/hooks/pre-push" "$TARGET_REPO_PATH/.git/hooks/pre-push"
echo "Git Runner successfully installed for $TARGET_REPO_PATH"

