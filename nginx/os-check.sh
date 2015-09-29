#!/bin/sh
# Detects which OS and if it is Linux then it will detect which Linux
# Distribution.

OS=`uname -s`

if [ "${OS}" = "SunOS" ] ; then
    OSSTR=Solaris
elif [ "${OS}" = "AIX" ] ; then
    OSSTR="${OS} `oslevel` (`oslevel -r`)"
elif [ "${OS}" = "Linux" ] ; then
    if [ -f /etc/redhat-release ] ; then
        DIST='RedHat'
    elif [ -f /etc/SuSE-release ] ; then
        OSSTR=Suse
    elif [ -f /etc/mandrake-release ] ; then
        OSSTR=Mandrake
    elif [ -f /etc/debian_version ] ; then
        OSSTR=Debian
    fi
    if [ -f /etc/UnitedLinux-release ] ; then
        OSSTR=Untitles
    fi
fi

echo ${OSSTR}