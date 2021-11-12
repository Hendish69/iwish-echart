    <aixm-message:hasMember>
        <aixm:VerticalStructure gml:id="pAixm01gml">
            <gml:identifier>pAixmID</gml:identifier>
            <aixm:timeSlice>
                <aixm:VerticalStructureTimeSlice gml:id="pAixm02gml">
                    <gml:validTime>
                        <gml:TimePeriod gml:id="pAixm03gml">
                            <gml:beginPosition>pAixmTime</gml:beginPosition>
                            <gml:endPosition indeterminatePosition="unknown"/>
                        </gml:TimePeriod>
                    </gml:validTime>
                    <aixm:interpretation>BASELINE</aixm:interpretation>
                    <aixm:sequenceNumber>1</aixm:sequenceNumber>
                    <aixm:type>pAixmName</aixm:type>
                    <aixm:part>
                        <aixm:VerticalStructurePart>
                            <aixm:verticalExtent uom="FT">pAixmelev1</aixm:verticalExtent>
                            <aixm:horizontalProjection_surfaceExtent>
                                <aixm:ElevatedSurface srsName="urn:ogc:def:crs:OGC:1.3:CRS84" gml:id="pAixm04gml">
                                    <gml:patches>
                                        <gml:PolygonPatch>
                                            <gml:exterior>
                                                <gml:LinearRing>
                                                    <gml:posList>pAixmPoly</gml:posList>
                                                </gml:LinearRing>
                                            </gml:exterior>
                                        </gml:PolygonPatch>
                                    </gml:patches>
                                    <aixm:elevation uom="FT">pAixmelev2</aixm:elevation>
                                </aixm:ElevatedSurface>
                            </aixm:horizontalProjection_surfaceExtent>
                        </aixm:VerticalStructurePart>
                    </aixm:part>
                </aixm:VerticalStructureTimeSlice>
            </aixm:timeSlice>
        </aixm:VerticalStructure>
    </aixm-message:hasMember>
