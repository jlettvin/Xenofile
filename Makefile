all:	Xenofile.html

Xenofile.html:	Xenofile.php Makefile
	# Force generation of keyword failures
	rm -f $@
	# 1st run generates keyword failures
	-php $< > $@
	# 2nd run picks up keywords from 1st run failures
	php $< > $@
