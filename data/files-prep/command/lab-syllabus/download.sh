# Remember to set the correct value for $TCE2
# by doing this...
# export TCE2=https://whatever.registrar.gatech.edu/foo/bar/
# and be sure to have that trailing slash, too.

for i in `cat data/files-prep/csv/LabSyllabus/_lab-syllabi-file-list.txt`; do wget "$TCE2${i}" --no-check-certificate; mv "${i}" "data/files-prep/csv/LabSyllabus/"; done
