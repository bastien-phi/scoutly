import { SVGAttributes } from 'react';
import ScoutlyBanner from '@assets/scoutly-banner.svg?react';

export default function AppLogo(props: SVGAttributes<SVGElement>) {
    return (
        <ScoutlyBanner {...props} height={undefined} width={undefined} />
    );
}
