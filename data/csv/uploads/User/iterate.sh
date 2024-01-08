# Remember to set the correct value for $TCE2
# by doing this...
# export TCE2=https://whatever.registrar.gatech.edu/foo/bar/
# and be sure to have that trailing slash, too.

for i in `cat _user-file-list.txt`; do wget "$TCE2${i}" --no-check-certificate; done