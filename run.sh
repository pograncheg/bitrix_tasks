#!/bin/bash

source ./operations.sh

while getopts "cdn:us:" OPT
do

    case "$OPT" in
    "n")
        f_download_backup "$OPTARG"
        f_create_log_dirs
        f_create_dbdump_dir
        f_unpack_backup
    ;;
    "c")
        f_create_log_dirs
        f_create_dbdump_dir
    ;;
    "d")
        f_recreate_log_dirs
    ;;
    "u")
        f_unpack_backup
    ;;
    "s")
        f_create_additional_site "$OPTARG"
    ;;
    "*")
    ;;
    esac
done
