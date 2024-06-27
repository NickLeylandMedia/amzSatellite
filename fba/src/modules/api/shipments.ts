//Function to get shipments from the API
async function getShipments() {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/get.php"
  ).then((res) => res.json());
}

async function getOneShipment(id: number) {
  return await fetch(
    `https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/get.php`
  ).then((res) => res.json());
}

async function getManifest(id: number | null) {
  if (!id) return;
  return await fetch(
    `https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/getmanifest.php?id=${id}`
  ).then((res) => res.json());
}

async function createShipment(name: string, date: string, summary: string) {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/create.php",
    {
      method: "POST",
      body: JSON.stringify({
        name: name,
        date: date,
        summary: summary,
      }),
    }
  ).then((res) => res.json());
}

export { createShipment, getShipments, getManifest };
