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
import {
  getShipments,
  getManifest,
  getShipmentItems,
} from "@/modules/api/shipments";
import { useQuery } from "@tanstack/react-query";

/* Component Interfaces */
interface Props {}

/* Component */
const Home: React.FC<Props> = () => {
  /* State Variables */
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
  }
  /* End Functions */

  /* Effects */
  //Shipment Query
  const shipmentQuery = useQuery({
    queryKey: ["shipmentQuery"],
    queryFn: () => getShipments().then((data) => data),
    refetchInterval: 30000,
  });

  const manifestQuery = useQuery({
    queryKey: ["manifestQuery"],
    queryFn: () => getShipmentItems().then((data) => data),
    refetchInterval: 30000,
  });

  /* End Effects */

  /* Component Return Statement */
  if (
    target &&
    shipmentQuery.isFetched &&
    shipmentQuery.data.length > 0 &&
    manifestQuery.isFetched &&
    manifestQuery.data.length > 0
  ) {
    //Get Manifest Data
    const targManifest = manifestQuery.data.filter((item: any) => {
      if (item && item.shipment_id === target) {
        return item;
      }
    });
    //Get Shipment Data
    const targData = shipmentQuery.data.find((item: any) => item.id === target);
    //Return Shipment Detail Component
    return (
      <ShipmentDetail
        data={targData}
        manifest={targManifest}
        clearer={clearManifestAndTarget}
        refetch={manifestQuery.refetch}
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
            shipments={shipmentQuery.data}
            modeSetter={setPageMode}
            targeter={setTarget}
          />
        ) : null}
        {pageMode && pageMode === "form" ? (
          <ShipmentForm
            modeSetter={setPageMode}
            refetch={shipmentQuery.refetch}
            targeter={setTarget}
          />
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
