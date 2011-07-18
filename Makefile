cv.pdf : cv.tex
	pdftex --jobname=cv-vit-brunner cv.tex

cv.tex : cv.tex.php
	php cv.tex.php > cv.tex

clean :
	rm -f cv.tex cv-vit-brunner*
