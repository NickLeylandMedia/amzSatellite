//Function to get shipments from the API
async function getShipments() {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/get.php"
  ).then((res) => res.json());
}

async function getShipmentItems() {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/allmanifests.php"
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

async function pushToShipment(
  shipmentId: number,
  sku: string,
  quantity: number
) {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/add.php",
    {
      method: "POST",
      body: JSON.stringify({
        shipment_id: shipmentId,
        sku: sku,
        quantity: quantity,
      }),
    }
  ).then((res) => res.json());
}

async function bulkPushToShipment(items: any[]) {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/fbamanifest/src/scripts/addbulk.php",
    {
      method: "POST",
      body: JSON.stringify({
        items: items,
      }),
    }
  ).then((res) => res.json());
}

export {
  createShipment,
  getShipments,
  getManifest,
  getShipmentItems,
  pushToShipment,
  bulkPushToShipment,
};
