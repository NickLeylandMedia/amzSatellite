/* Library Imports */
//React
import React, { useEffect, useState } from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */
import ManifestLog from "@/components/manifest/ManifestLog";
import ShipmentDetail from "@/components/manifest/ShipmentDetail";
import ShipmentForm from "@/components/manifest/ShipmentForm";

/* Module Imports */
import { getShipments, getManifest } from "@/modules/api/shipments";
import { useQuery } from "@tanstack/react-query";

/* Component Interfaces */
interface Props {}

/* Component */
const Home: React.FC<Props> = () => {
  // console.log(shipments);
  /* State Variables */
  const [shipments, setShipments] = useState([]);
  const [pageMode, setPageMode] = useState("manifest");
  const [target, setTarget] = useState(null);
  const [manifest, setManifest] = useState([]);
  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Render Logic */
  /* End Render Logic */

  /* Functions */
  function clearManifestAndTarget() {
    setTarget(null);
    setManifest([]);
  }
  /* End Functions */

  /* Effects */
  //Shipment Query
  useQuery({
    queryKey: ["shipmentQuery"],
    queryFn: () =>
      getShipments().then((data) => {
        setShipments(data);
        return data.json();
      }),
    refetchInterval: 1000,
  });

  useQuery({
    queryKey: ["manifestQuery"],
    queryFn: () =>
      getManifest(target).then((data) => {
        setManifest(data);
        return data.json();
      }),
    refetchInterval: 1000,
  });

  /* End Effects */

  /* Component Return Statement */
  if (target && shipments && shipments.length > 0) {
    const targData = shipments.find((shipment: any) => shipment.id === target);
    return (
      <ShipmentDetail
        data={targData}
        manifest={manifest}
        clearer={clearManifestAndTarget}
      />
    );
  }

  return (
    <div className="Home page">
      {/* Header Start */}
      <header></header>
      {/* Header End */}
      {/* Content Start */}
      <div className="mainContent">
        {pageMode && pageMode === "manifest" ? (
          <ManifestLog
            shipments={shipments}
            modeSetter={setPageMode}
            targeter={setTarget}
          />
        ) : null}
        {pageMode && pageMode === "form" ? (
          <ShipmentForm modeSetter={setPageMode} />
        ) : null}
      </div>
      {/* Content End */}
      {/* Footer Start */}
      {/* <Footer /> */}
      {/* Footer End */}
    </div>
  );
};

/* Export Statement */
export default Home;
