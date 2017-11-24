junit-viewer --results=bin/tests/result.xml --save=bin/tests/report.html --minify=false --contracted
if [ -d bin/tests/errors/ ]; then mkdir $CIRCLE_ARTIFACTS/screenshots/$1; cp bin/tests/errors/* $CIRCLE_ARTIFACTS/screenshots/$1; rm -rf bin/tests/errors/; fi
