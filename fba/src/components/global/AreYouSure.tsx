/* Library Imports */
//React
import React from "react";

/* Stylesheet Imports */

/* Image Imports */
import { MdClose } from "react-icons/md";

/* Component Imports */

/* Module Imports */
import {
  deleteShipmentByID,
  // clearShipmentItemsByID,
} from "@/modules/api/shipments";

/* Component Interfaces */
interface Props {
  shipmentID: number;
  closer: any;
  clearer: any;
  shipmentRefetch: any;
}

/* Component */
const AreYouSure: React.FC<Props> = ({
  closer,
  shipmentID,
  clearer,
  shipmentRefetch,
}) => {
  /* State Variables */
  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Functions */
  async function deleteAndClear() {
    await deleteShipmentByID(shipmentID);
    shipmentRefetch();
    clearer();
  }
  /* End Functions */

  /* Effects */
  /* End Effects */

  /* Component Return Statement */
  return (
    <div className="AreYouSure border border-black bg-white h-[400px] w-3/5 absolute top-[20%] left-[20%] rounded-2xl">
      <MdClose
        className="absolute top-7 right-7 text-3xl cursor-pointer text-black hover:text-red-500"
        onClick={(e: any) => closer(false)}
      />
      <div className="flex flex-col items-center justify-center h-full">
        <p className="text-2xl">Really Delete Shipment?</p>
        <p className="text-xl">Like.... Really really?</p>
        <div className="flex flex-row justify-evenly w-2/5 m-10">
          <button
            className="bg-red-500 text-white py-2 rounded-lg w-[200px] hover:bg-red-700"
            onClick={(e) => deleteAndClear()}
          >
            Yes Really
          </button>
          <button className="bg-green-500 text-white py-2 rounded-lg w-[200px] hover:bg-green-700">
            No I'm Scared
          </button>
        </div>
      </div>
    </div>
  );
};

/* Export Statement */
export default AreYouSure;
