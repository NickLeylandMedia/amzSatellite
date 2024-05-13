//Function to get shipments from the API
async function getShipments() {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/get.php"
  ).then((res) => res.json());
}

export { getShipments };
