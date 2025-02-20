#!/bin/bash
ls -l | awk '{
    if($1 == "drwxr-xr-x") {
        printf "\033[32m" $0 "\033[0m\n"
    } else if($1 == "drwxrwxrwx") {
        printf "\033[32m" $0 "\033[0m\n"
    } else if($1 == "drwxrwx---") {
        printf "\033[32m" $0 "\033[0m\n"
    } else if($1 == "-rw-rw-rw-") {
        printf "\033[32m" $0 "\033[0m\n"
    } else if($1 == "-rw-rw----") {
        printf "\033[32m" $0 "\033[0m\n"
    }
