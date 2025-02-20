


curl --header "PRIVATE-TOKEN: token" "https://gitlab.com/api/v4/projects?membership=true"
curl --header "PRIVATE-TOKEN: token" "https://gitlab.com/api/v4/projects?membership=true&per_page=100" | jq '.[] | {id, name, web_url}'

curl --request DELETE --header "PRIVATE-TOKEN: token" "https://gitlab.com/api/v4/projects/sheraz2435503%2Fdemo"

sudo dnf install glab
glab auth login
glab repo list

glab project list
sudo dnf upgrade glab
glab --version
xdg-open https://gitlab.com/Sherazameer/See
