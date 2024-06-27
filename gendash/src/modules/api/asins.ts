// https://api.blackhalloutfitters.com/debug/amzsatellite/src/scripts/asins/getall.php

//Function to get shipments from the API
async function getAsins() {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/amzsatellite/src/scripts/asins/getall.php"
  ).then((res) => res.json());
}

async function getFromDB() {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/amzsatellite/src/scripts/asins/getdbskus.php"
  ).then((res) => res.json());
}

async function nukeAsins() {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/amzsatellite/src/scripts/asins/nuke.php"
  ).then((res) => res.json());
}

export { getAsins, getFromDB, nukeAsins };
