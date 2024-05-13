/* Library Imports */
//React
import React, { useEffect, useState } from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */
import ManifestLog from "@/components/manifest/ManifestLog";
import ShipmentForm from "@/components/manifest/ShipmentForm";

/* Module Imports */
import { getShipments } from "@/modules/api/shipments";

/* Component Interfaces */
interface Props {}

/* Component */
const Home: React.FC<Props> = () => {
  // console.log(shipments);
  /* State Variables */
  const [shipments, setShipments] = useState([]);
  const [pageMode, setPageMode] = useState("manifest");
  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Render Logic */
  /* End Render Logic */

  /* Functions */
  /* End Functions */

  /* Effects */
  useEffect(() => {
    getShipments().then((data) => {
      setShipments(data);
    });
  }, []);
  /* End Effects */

  /* Component Return Statement */
  return (
    <div className="Home page">
      {/* Header Start */}
      <header></header>
      {/* Header End */}
      {/* Content Start */}
      <div className="mainContent">
        <ManifestLog shipments={shipments} />
        <ShipmentForm />
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
