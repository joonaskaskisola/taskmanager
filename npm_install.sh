#!/bin/bash

PACKAGES_JS=('axios' 'react-select' 'react' 'react-dom');
PACKAGES_CSS=('react-notifications' 'react-select');
PACKAGES_FONT=('react-notifications');

for p in ${PACKAGES_JS[@]}; do
	echo "(JS) Installing $p..";
	$(cp ./node_modules/$(echo $p)/dist/*.min.js ./web/js/);
done

for p in ${PACKAGES_CSS[@]}; do
	echo "(CSS) Installing $p..";
	$(cp ./node_modules/$(echo $p)/dist/*.css ./web/css/);
done

for p in ${PACKAGES_FONT[@]}; do
	echo "(FONT) Installing $p..";
	$(cp -R ./node_modules/$(echo $p)/dist/fonts/* ./web/css/fonts/);
done
