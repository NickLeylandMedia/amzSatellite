//function to post array of asins to the API
async function OTScope(asins: any[]) {
  return await fetch(
    "https://api.blackhalloutfitters.com/debug/amzsatellite/src/scripts/audits/oldtown.php",
    {
      method: "POST",
      body: JSON.stringify({ asins: asins }),
    }
  ).then((res) => res.json());
}

export { OTScope };
