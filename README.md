[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/joonaskaskisola/taskmanager)

# Task, time tracking & invoice manager

This software is designed to manage customers information, users (+working hours), repetitive tasks, invoices etc..

## AWS instructions
- https://github.com/joonaskaskisola/aws-taskmanager

## TODO
- <s>Change date() calls to chronos or something similar</s>
- <s>Convert to ReactJS app (wip)</s>
- Form layouts
- Ditch symfony

## Installation

```
source insert-configs.sh
npm install
composer install
webpack
app/console doctrine:schema:update --force
```

## Dev

```
app/console server:start
node ./node_modules/webpack/bin/webpack.js --watch --progress
```

## Load random data

```
app/console seed:load
```

## Contributing

Fork & create own branch, do pull request

## Licence

```
MIT License

Copyright (c) 2017 Joonas Kaskisola

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
