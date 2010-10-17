all: lol_linux

lol_linux:
	cd src/;pcc -v exec.php -o ../bin/lol;

clean: 
	-rm -f bin/*;rm -f pkg/*;find . -name "*~" | xargs -IMYFNAME rm "MYFNAME";find . -name "*.o" | xargs -IMYFNAME rm "MYFNAME";

linuxpkg: lol_linux
	cp README_pkg pkg/README; cp bin/lol pkg;cp -Rd examples pkg;cd pkg;zip -v -r mc-lolcode_linux_0.3.zip * -xi *svn* *~;rm lol;rm README;rm -rf examples/
