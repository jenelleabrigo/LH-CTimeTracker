# 他プロジェクトに利用する場合

テンプレートファイルをクローン

```bash
$ git clone --recursive git@gitlab.com:lionheart-group/lh-task-runner.git

# or

$ git clone --recursive https://gitlab.com/lionheart-group/lh-fegg-skelton.git
```

テンプレートのGit情報を削除

```bash
$ rm -fr .git
$ rm -fr .gitmodules
$ rm -fr task-runner/.git
```

指定リポジトリを指定

```bash
$ git remote add origin (リポジトリURL)
```

# データベースマイグレーション

```
./fegg-cli migratio
```
