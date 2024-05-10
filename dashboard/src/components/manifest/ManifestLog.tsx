/* Library Imports */
//React
import React from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */

/* Module Imports */

/* Component Interfaces */
interface Props {}

/* Component */
const ManifestLog: React.FC<Props> = () => {
  /* State Variables */
  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Functions */
  /* End Functions */

  /* Effects */
  /* End Effects */

  /* Component Return Statement */
  return (
    <div className="ManifestLog w-[95%] mx-auto my-5">
      <h2 className="text-black text-center text-4xl">FBA Shipment Manifest</h2>
      <div className="manifestActions"></div>
      <div className="shipmentLog grid grid-cols-3 w-full mx-auto my-5"></div>
    </div>
  );
};

/* Export Statement */
export default ManifestLog;
